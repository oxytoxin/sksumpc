<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $capital_subscription_billing_id
 * @property int $capital_subscription_id
 * @property int $member_id
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @property-read \App\Models\CapitalSubscriptionBilling $capital_subscription_billing
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereCapitalSubscriptionBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CapitalSubscriptionBillingPayment extends Model
{
    use HasFactory;

    public function capital_subscription()
    {
        return $this->belongsTo(CapitalSubscription::class);
    }

    public function capital_subscription_billing()
    {
        return $this->belongsTo(CapitalSubscriptionBilling::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
