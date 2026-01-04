<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $year
 * @property array<array-key, mixed> $officers
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereOfficers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereYear($value)
 * @mixin \Eloquent
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
