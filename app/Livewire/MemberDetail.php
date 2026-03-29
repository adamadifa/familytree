<?php

namespace App\Livewire;

use Livewire\Component;

class MemberDetail extends Component
{
    public $memberId;
    public $member;
    public $canEdit = false;

    protected $listeners = ['memberSelected' => 'loadMember'];

    public function loadMember($id)
    {
        $this->memberId = $id;
        $this->member = \App\Models\FamilyMember::with(['parents', 'children'])->find($id);
        
        if ($this->member) {
            $this->canEdit = auth()->user()->canManage($this->member);
        } else {
            $this->canEdit = false;
        }
    }

    public function toggleDivorce($spouseId)
    {
        if (!$this->memberId || !$this->member) return;
        abort_if(!auth()->user()->canManage($this->member), 403, 'Akses Ditolak.');

        $rel = \App\Models\Relationship::where('relationship_type', 'spouse')
            ->where(function($q) use ($spouseId) {
                $q->where('person_a_id', $this->memberId)->where('person_b_id', $spouseId)
                  ->orWhere('person_a_id', $spouseId)->where('person_b_id', $this->memberId);
            })->first();

        if ($rel) {
            $meta = json_decode($rel->metadata, true) ?: [];
            if (($meta['status'] ?? '') === 'divorced') {
                unset($meta['status']);
            } else {
                $meta['status'] = 'divorced';
            }
            $rel->update(['metadata' => json_encode($meta)]);
            
            $this->loadMember($this->memberId);
            $this->dispatch('treeDataUpdated');
        }
    }

    public function deleteMember()
    {
        if ($this->memberId && $this->member) {
            abort_if(!auth()->user()->canManage($this->member), 403, 'Akses Ditolak.');
            
            \App\Models\Relationship::where('person_a_id', $this->memberId)
                ->orWhere('person_b_id', $this->memberId)
                ->delete();

            \App\Models\FamilyMember::where('id', $this->memberId)->delete();

            $this->member = null;
            $this->memberId = null;
            $this->dispatch('refreshTree');
        }
    }

    public function render()
    {
        return view('livewire.member-detail');
    }
}
