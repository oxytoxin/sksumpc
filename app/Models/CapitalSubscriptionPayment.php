<?php

namespace App\Models;

use DB;
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
            if ($cbu_payment->capital_subscription->payments()->count()) {
                DB::beginTransaction();
                $cbu = $cbu_payment->capital_subscription;
                $amount_paid = $cbu_payment->amount;
                while ($amount_paid > 0) {
                    $active_capital_subscription_amortization = $cbu->active_capital_subscription_amortization;
                    if (! $active_capital_subscription_amortization) {
                        break;
                    }
                    if ($active_capital_subscription_amortization->arrears > 0) {
                        $amount = $amount_paid > $active_capital_subscription_amortization->arrears ? $active_capital_subscription_amortization->arrears : $amount_paid;
                        $active_capital_subscription_amortization->update([
                            'amount_paid' => $active_capital_subscription_amortization->amount_paid + $amount,
                        ]);
                    } else {
                        $amount = $amount_paid > $active_capital_subscription_amortization->amount ? $active_capital_subscription_amortization->amount : $amount_paid;
                        $active_capital_subscription_amortization->update([
                            'amount_paid' => $amount,
                        ]);
                    }
                    $amount_paid -= $amount;
                    $cbu->load('active_capital_subscription_amortization');
                }
                DB::commit();
            }
        });
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
