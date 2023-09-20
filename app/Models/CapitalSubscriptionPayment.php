<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalSubscriptionPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function capital_subscription()
    {
        return $this->belongsTo(CapitalSubscription::class);
    }

    protected static function booted(): void
    {
        static::creating(function (CapitalSubscriptionPayment $cbu_payment) {
            $cbu_payment->running_balance = $cbu_payment->capital_subscription->outstanding_balance - $cbu_payment->amount;
        });
    }
}
