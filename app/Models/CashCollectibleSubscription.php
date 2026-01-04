<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount
 * @property int $number_of_terms
 * @property numeric|null $billable_amount
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleAccount $cash_collectible_account
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereBillableAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CashCollectibleSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'billable_amount' => 'decimal:4',
    ];

    public function cash_collectible_account()
    {
        return $this->belongsTo(CashCollectibleAccount::class, 'account_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
