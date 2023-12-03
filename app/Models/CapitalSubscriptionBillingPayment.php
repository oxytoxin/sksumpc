<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalSubscriptionBillingPayment extends Model
{
    use HasFactory;

    public function capital_subscription_amortization()
    {
        return $this->belongsTo(CapitalSubscriptionAmortization::class);
    }
}
