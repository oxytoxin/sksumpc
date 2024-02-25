<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCapitalSubscriptionBillingPayment
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
