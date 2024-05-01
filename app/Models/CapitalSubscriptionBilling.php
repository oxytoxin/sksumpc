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
        'for_or' => 'boolean',
    ];

    public function capital_subscription_billing_payments()
    {
        return $this->hasMany(CapitalSubscriptionBillingPayment::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function generateReferenceNumber(self $capitalSubscriptionBilling)
    {
        return 'CBUBILLING' . '-' . today()->format('Y-m-') . str_pad($capitalSubscriptionBilling->id, 6, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::created(function (CapitalSubscriptionBilling $capitalSubscriptionBilling) {
            DB::beginTransaction();
            $capitalSubscriptionBilling->reference_number = $capitalSubscriptionBilling->generateReferenceNumber($capitalSubscriptionBilling);
            CapitalSubscription::query()
                ->where('is_common', true)
                ->where('outstanding_balance', '>', 0)
                ->withCount('payments')
                ->when($capitalSubscriptionBilling->member_type_id, fn ($query, $value) => $query->whereRelation('member', 'member_type_id', $value))
                ->each(function ($cbu) use ($capitalSubscriptionBilling) {
                    $amount_due = $cbu->payments_count > 0 ? $cbu->monthly_payment : $cbu->initial_amount_paid;
                    CapitalSubscriptionBillingPayment::firstOrCreate([
                        'member_id' => $cbu->member_id,
                        'capital_subscription_billing_id' => $capitalSubscriptionBilling->id,
                    ], [
                        'capital_subscription_id' => $cbu->id,
                        'amount_due' => $cbu->monthly_payment,
                        'amount_paid' => $cbu->monthly_payment,
                    ]);
                });
            $capitalSubscriptionBilling->cashier_id = auth()->id();
            $capitalSubscriptionBilling->save();
            DB::commit();
        });
    }
}
