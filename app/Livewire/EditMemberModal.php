<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\FamilyMember;

class EditMemberModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $memberId;
    public $canEdit = false;

    public $first_name = '';
    public $last_name = '';
    public $gender = 'M';
    public $birth_date = '';
    public $death_date = '';
    public $birth_place = '';
    public $bio = '';
    
    public $photo;
    public $existingPhoto;

    // Parent Selection Fields
    public $existingParents = [];
    public $spousesOfPrimaryParent = [];
    public $otherParentId = '';

    protected $listeners = ['openEditMemberModal' => 'openModal'];

    public function openModal($id)
    {
        // Safe unpack if Livewire v3 payload is passed as an associative array
        $this->memberId = is_array($id) && isset($id['id']) ? $id['id'] : $id;
        
        $member = FamilyMember::findOrFail($this->memberId);
        $this->canEdit = auth()->user()->canManage($member);
        
        $this->first_name = $member->first_name;
        $this->last_name = $member->last_name;
        $this->gender = $member->gender;
        $this->birth_date = $member->birth_date;
        $this->death_date = $member->death_date;
        $this->birth_place = $member->birth_place;
        $this->bio = $member->bio;
        
        $this->existingPhoto = $member->photo_path;
        $this->photo = null;
        
        // Parent Selection Logic
        $this->existingParents = $member->parents->pluck('id')->toArray();
        $this->spousesOfPrimaryParent = [];
        $this->otherParentId = '';

        if (count($this->existingParents) === 1) {
            $primaryParent = FamilyMember::find($this->existingParents[0]);
            if ($primaryParent) {
                $this->spousesOfPrimaryParent = $primaryParent->spouses;
            }
        }
        
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'required|in:M,F,O',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'birth_place' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $member = FamilyMember::findOrFail($this->memberId);
        abort_if(!auth()->user()->canManage($member), 403, 'Akses Ditolak.');
        
        $photoPath = $member->photo_path;
        
        if ($this->photo) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->photo->store('profiles', 'public');
        }

        $member->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date ? $this->birth_date : null,
            'death_date' => $this->death_date ? $this->death_date : null,
            'birth_place' => $this->birth_place,
            'bio' => $this->bio,
            'photo_path' => $photoPath,
        ]);

        // Retroactive Second Parent Linking
        if (count($this->existingParents) === 1 && $this->otherParentId) {
            $exists = \App\Models\Relationship::where('person_a_id', $this->otherParentId)
                ->where('person_b_id', $member->id)
                ->where('relationship_type', 'parent_child')
                ->exists();
                
            if (!$exists) {
                \App\Models\Relationship::create([
                    'person_a_id' => $this->otherParentId,
                    'person_b_id' => $member->id,
                    'relationship_type' => 'parent_child',
                    'created_by' => auth()->id() ?? 1,
                ]);
            }
        }

        $this->closeModal();
        
        $this->dispatch('memberSelected', id: $member->id);
        $this->dispatch('treeDataUpdated');
    }

    public function render()
    {
        return view('livewire.edit-member-modal');
    }
}
