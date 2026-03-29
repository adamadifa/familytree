<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_a_id', 'person_b_id', 'relationship_type', 'metadata', 'created_by'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function personA()
    {
        return $this->belongsTo(FamilyMember::class, 'person_a_id');
    }

    public function personB()
    {
        return $this->belongsTo(FamilyMember::class, 'person_b_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
