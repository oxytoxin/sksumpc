<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCapitalSubscriptionPayment
 */
class CapitalSubscriptionPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'deposit' => 'decimal:4',
        'withdrawal' => 'decimal:4',
        'running_balance' => 'decimal:4',
        'transaction_date' => 'immutable_date',
    ];

    public function capital_subscription()
    {
        return $this->belongsTo(CapitalSubscription::class);
    }

    protected static function booted(): void
    {
        static::creating(function (CapitalSubscriptionPayment $cbu_payment) {
            $cbu_payment->cashier_id = auth()->id();
            $cbu_payment->running_balance = $cbu_payment->capital_subscription->outstanding_balance - $cbu_payment->amount;
        });
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
