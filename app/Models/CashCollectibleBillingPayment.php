<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cash_collectible_billing_id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleAccount $cash_collectible_account
 * @property-read \App\Models\CashCollectibleBilling $cash_collectible_billing
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereCashCollectibleBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CashCollectibleBillingPayment extends Model
{
    use HasFactory;

    public function cash_collectible_account()
    {
        return $this->belongsTo(CashCollectibleAccount::class, 'account_id');
    }

    public function cash_collectible_billing()
    {
        return $this->belongsTo(CashCollectibleBilling::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
