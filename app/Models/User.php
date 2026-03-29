<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'family_member_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function familyMember()
    {
        return $this->belongsTo(\App\Models\FamilyMember::class);
    }

    /**
     * Determine if the user can manage the target family member.
     * Admins can manage anyone.
     * Regular members can manage themselves, their descendants, and the spouses of themselves/descendants.
     */
    public function canManage(\App\Models\FamilyMember $target)
    {
        if ($this->role === 'admin') {
            return true;
        }

        if (!$this->family_member_id) {
            return false;
        }

        $ancestorId = $this->family_member_id;

        if ($target->id === $ancestorId) {
            return true;
        }

        if ($this->isDescendantOf($target->id, $ancestorId)) {
            return true;
        }

        // Check if target is a spouse of the user or a spouse of a descendant
        $spouses = \App\Models\Relationship::where('relationship_type', 'spouse')
            ->where(function ($q) use ($target) {
                $q->where('person_a_id', $target->id)
                  ->orWhere('person_b_id', $target->id);
            })
            ->get()
            ->map(function ($rel) use ($target) {
                return $rel->person_a_id === $target->id ? $rel->person_b_id : $rel->person_a_id;
            });

        foreach ($spouses as $sId) {
            if ($sId === $ancestorId || $this->isDescendantOf($sId, $ancestorId)) {
                return true;
            }
        }

        return false;
    }

    protected function isDescendantOf($descendantId, $ancestorId, $visited = [])
    {
        if ($descendantId == $ancestorId) {
            return true;
        }
        
        if (in_array($descendantId, $visited)) {
            return false; // Prevent infinite loops
        }
        $visited[] = $descendantId;

        $parents = \App\Models\Relationship::where('person_b_id', $descendantId)
            ->where('relationship_type', 'parent_child')
            ->pluck('person_a_id');

        foreach ($parents as $pId) {
            if ($pId == $ancestorId) {
                return true;
            }
            if ($this->isDescendantOf($pId, $ancestorId, $visited)) {
                return true;
            }
        }

        return false;
    }
}
