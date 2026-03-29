<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class AddMemberModal extends Component
{
    use WithFileUploads;
    public $isOpen = false;
    public $relativeId;
    public $relationshipType; // parent, child, spouse

    public $firstName;
    public $lastName;
    public $gender = 'M';
    public $birthDate;
    public $birthPlace;
    public $bio;
    public $photo;
    public $otherParentId;
    public $spouses = [];

    protected $listeners = ['openAddMemberModal' => 'open'];

    public function open($relativeId = null, $relationshipType = 'root')
    {
        $this->reset(['firstName', 'lastName', 'gender', 'birthDate', 'birthPlace', 'bio', 'photo', 'otherParentId', 'spouses']);
        
        $this->relativeId = $relativeId;
        $this->relationshipType = $relationshipType;

        if ($relativeId) {
            $member = \App\Models\FamilyMember::findOrFail($relativeId);
            abort_if(!auth()->user()->canManage($member), 403, 'Anda tidak memiliki hak akses untuk menambahkan anggota pada cabang keluarga ini.');
            
            // Fetch potential other parents (spouses of the relative)
            if ($this->relationshipType === 'child') {
                $this->spouses = $member->spouses;
                if (count($this->spouses) > 0) {
                    $this->otherParentId = $this->spouses[0]['id'];
                }
            }
        } else {
            abort_if(auth()->user()->role !== 'admin', 403, 'Hanya administrator yang dapat membuat keluarga inti (root) baru.');
        }

        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'gender' => 'required|in:M,F,O',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('profiles', 'public');
        }

        if ($this->relativeId) {
            $member = \App\Models\FamilyMember::findOrFail($this->relativeId);
            abort_if(!auth()->user()->canManage($member), 403, 'Akses Ditolak.');
        } else {
            abort_if(auth()->user()->role !== 'admin', 403, 'Akses Ditolak.');
        }

        $newMember = \App\Models\FamilyMember::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'gender' => $this->gender,
            'birth_date' => $this->birthDate,
            'birth_place' => $this->birthPlace,
            'bio' => $this->bio,
            'photo_path' => $photoPath,
            'created_by' => auth()->id() ?? 1, // Fallback for testing
        ]);

        // Create relationship - Parent 1
        if ($this->relationshipType === 'parent' && $this->relativeId) {
            \App\Models\Relationship::create([
                'person_a_id' => $newMember->id,
                'person_b_id' => $this->relativeId,
                'relationship_type' => 'parent_child',
                'created_by' => auth()->id() ?? 1,
            ]);
        } elseif ($this->relationshipType === 'child' && $this->relativeId) {
            \App\Models\Relationship::create([
                'person_a_id' => $this->relativeId,
                'person_b_id' => $newMember->id,
                'relationship_type' => 'parent_child',
                'created_by' => auth()->id() ?? 1,
            ]);

            // Create relationship - Parent 2 (if selected)
            if ($this->otherParentId) {
                \App\Models\Relationship::create([
                    'person_a_id' => $this->otherParentId,
                    'person_b_id' => $newMember->id,
                    'relationship_type' => 'parent_child',
                    'created_by' => auth()->id() ?? 1,
                ]);
            }
        } elseif ($this->relationshipType === 'spouse' && $this->relativeId) {
            \App\Models\Relationship::create([
                'person_a_id' => $this->relativeId,
                'person_b_id' => $newMember->id,
                'relationship_type' => 'spouse',
                'created_by' => auth()->id() ?? 1,
            ]);
        } elseif ($this->relationshipType === 'sibling') {
            $parentRel = \App\Models\Relationship::where('person_b_id', $this->relativeId)->where('relationship_type', 'parent_child')->first();
            if ($parentRel) {
                \App\Models\Relationship::create([
                    'person_a_id' => $parentRel->person_a_id,
                    'person_b_id' => $newMember->id,
                    'relationship_type' => 'parent_child',
                    'created_by' => auth()->id() ?? 1,
                ]);
            }
        }

        $this->isOpen = false;
        $this->dispatch('refreshTree');
    }

    public function render()
    {
        return view('livewire.add-member-modal');
    }
}
