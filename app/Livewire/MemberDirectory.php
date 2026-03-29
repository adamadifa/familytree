<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class MemberDirectory extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = ['treeDataUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function deleteMember($id)
    {
        $member = FamilyMember::findOrFail($id);
        abort_if(!auth()->user()->canManage($member), 403, 'Akses Ditolak.');
        
        // Unlink all relationships first
        \App\Models\Relationship::where('person_a_id', $id)
            ->orWhere('person_b_id', $id)
            ->delete();
            
        // Delete photo if exists
        if ($member->photo_path && Storage::disk('public')->exists($member->photo_path)) {
            Storage::disk('public')->delete($member->photo_path);
        }
        
        $member->delete();
        
        $this->dispatch('treeDataUpdated');
    }

    public function render()
    {
        $query = FamilyMember::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('birth_place', 'like', '%' . $this->search . '%');
            });
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.member-directory', [
            'members' => $members
        ]);
    }
}
