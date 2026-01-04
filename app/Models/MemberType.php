<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property numeric $surcharge_rate
 * @property numeric $par_value
 * @property numeric $default_number_of_shares
 * @property numeric $default_amount_subscribed
 * @property numeric $minimum_initial_payment
 * @property int $initial_number_of_terms
 * @property int $additional_number_of_terms
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereAdditionalNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereDefaultAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereDefaultNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereInitialNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereMinimumInitialPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereSurchargeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MemberType extends Model
{
    use HasFactory;

    protected $casts = [
        'default_number_of_shares' => 'decimal:4',
        'default_amount_subscribed' => 'decimal:4',
        'minimum_initial_payment' => 'decimal:4',
        'par_value' => 'decimal:4',
        'surcharge_rate' => 'decimal:4',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
