<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOfficersList
 */
class OfficersList extends Model
{
    use HasFactory;

    protected $casts = [
        'officers' => 'array',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class)->withPivot('position_id');
    }

    public function getOfficersAttribute()
    {
        return $this->members->map(function ($member) {
            return ['member_id' => $member->id, 'position_id' => $member->pivot->position_id];
        });
    }
}
