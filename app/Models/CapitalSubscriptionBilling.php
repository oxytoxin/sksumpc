<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCapitalSubscriptionBilling
 */
class CapitalSubscriptionBilling extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'posted' => 'boolean',
    ];

    public function capital_subscription_billing_payments()
    {
        return $this->hasMany(CapitalSubscriptionBillingPayment::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted(): void
    {
        static::created(function (CapitalSubscriptionBilling $capitalSubscriptionBilling) {
            DB::beginTransaction();
            CapitalSubscriptionAmortization::where('billable_date', $capitalSubscriptionBilling->date->format('F Y'))->whereNull('amount_paid')->each(function ($ca) use ($capitalSubscriptionBilling) {
                CapitalSubscriptionBillingPayment::firstOrCreate([
                    'member_id' => $ca->capital_subscription->member_id,
                    'capital_subscription_billing_id' => $capitalSubscriptionBilling->id,
                    'capital_subscription_amortization_id' => $ca->id,
                ], [
                    'amount_due' => $ca->amount,
                    'amount_paid' => $ca->amount,
                ]);
            });
            $capitalSubscriptionBilling->cashier_id = auth()->id();
            $capitalSubscriptionBilling->save();
            DB::commit();
        });
    }
}
