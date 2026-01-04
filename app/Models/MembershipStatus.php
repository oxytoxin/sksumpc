<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $member_id
 * @property int $type
 * @property string|null $bod_resolution
 * @property \Carbon\CarbonImmutable $effectivity_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereBodResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereEffectivityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MembershipStatus extends Model
{
    use HasFactory;

    protected $casts = [
        'effectivity_date' => 'immutable_date',
    ];

    const ACCEPTANCE = 1;

    const TERMINATION = 2;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
