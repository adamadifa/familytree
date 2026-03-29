<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'birth_date', 'death_date',
        'birth_place', 'death_place', 'bio', 'photo_path', 'created_by', 'linked_user_id'
    ];

    public function relationships()
    {
        return $this->hasMany(Relationship::class, 'person_a_id')->orWhere('person_b_id', $this->id);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function linkedUser()
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }

    public function parents()
    {
        return $this->belongsToMany(FamilyMember::class, 'relationships', 'person_b_id', 'person_a_id')
            ->wherePivot('relationship_type', 'parent_child');
    }

    public function children()
    {
        return $this->belongsToMany(FamilyMember::class, 'relationships', 'person_a_id', 'person_b_id')
            ->wherePivot('relationship_type', 'parent_child');
    }

    public function getSpousesAttribute()
    {
        $rels = Relationship::where('relationship_type', 'spouse')
            ->where(function ($query) {
                $query->where('person_a_id', $this->id)
                      ->orWhere('person_b_id', $this->id);
            })->get();

        $spouseIds = $rels->map(function ($rel) {
            return $rel->person_a_id == $this->id ? $rel->person_b_id : $rel->person_a_id;
        })->unique();

        $spouses = self::whereIn('id', $spouseIds)->get()->keyBy('id');

        return $spouseIds->map(function ($id) use ($spouses, $rels) {
            $spouse = $spouses->get($id);
            if ($spouse) {
                $rel = $rels->first(function ($r) use ($id) {
                    return ($r->person_a_id == $this->id && $r->person_b_id == $id) ||
                           ($r->person_a_id == $id && $r->person_b_id == $this->id);
                });
                
                $meta = json_decode($rel ? $rel->metadata : null, true);
                $spouse->is_divorced = ($meta['status'] ?? '') === 'divorced';
            }
            return $spouse;
        })->filter();
    }

    public function getStepChildrenAttribute()
    {
        $spouseIds1 = Relationship::where('relationship_type', 'spouse')->where('person_a_id', $this->id)->pluck('person_b_id');
        $spouseIds2 = Relationship::where('relationship_type', 'spouse')->where('person_b_id', $this->id)->pluck('person_a_id');
        $spouseIds = $spouseIds1->concat($spouseIds2)->unique();
        
        $childrenOfSpousesIds = Relationship::where('relationship_type', 'parent_child')
            ->whereIn('person_a_id', $spouseIds)->pluck('person_b_id')->unique();
            
        $myChildrenIds = Relationship::where('relationship_type', 'parent_child')
            ->where('person_a_id', $this->id)->pluck('person_b_id')->unique();
            
        $stepChildrenIds = $childrenOfSpousesIds->diff($myChildrenIds);
        
        if ($stepChildrenIds->isEmpty()) return collect();
        return self::whereIn('id', $stepChildrenIds)->get();
    }

    public function getStepParentsAttribute()
    {
        $myParentIds = Relationship::where('relationship_type', 'parent_child')
            ->where('person_b_id', $this->id)->pluck('person_a_id')->unique();
            
        if ($myParentIds->isEmpty()) return collect();
        
        $spousesOfParentsIds1 = Relationship::where('relationship_type', 'spouse')
            ->whereIn('person_a_id', $myParentIds)->pluck('person_b_id');
        $spousesOfParentsIds2 = Relationship::where('relationship_type', 'spouse')
            ->whereIn('person_b_id', $myParentIds)->pluck('person_a_id');
            
        $spousesOfParentsIds = $spousesOfParentsIds1->concat($spousesOfParentsIds2)->unique();
        
        $stepParentIds = $spousesOfParentsIds->diff($myParentIds);
        
        if ($stepParentIds->isEmpty()) return collect();
        return self::whereIn('id', $stepParentIds)->get();
    }
}
