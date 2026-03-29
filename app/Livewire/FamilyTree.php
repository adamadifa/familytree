<?php

namespace App\Livewire;

use Livewire\Component;

class FamilyTree extends Component
{
    public $members;
    public $relationships;

    protected $listeners = ['refreshTree' => 'refreshTree'];

    public function mount()
    {
        $this->members = \App\Models\FamilyMember::all()->map(function ($member) {
            $member->can_manage = auth()->user()->canManage($member);
            return $member;
        });
        $this->relationships = \App\Models\Relationship::all();
    }

    public function refreshTree()
    {
        $this->members = \App\Models\FamilyMember::all()->map(function ($member) {
            $member->can_manage = auth()->user()->canManage($member);
            return $member;
        });
        $this->relationships = \App\Models\Relationship::all();
        $this->dispatch('treeDataUpdated', [
            'members' => $this->members,
            'relationships' => $this->relationships
        ]);
    }

    public function render()
    {
        return view('livewire.family-tree');
    }
}
