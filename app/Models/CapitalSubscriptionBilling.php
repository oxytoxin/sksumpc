<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'or_date' => 'immutable_date',
        'posted' => 'boolean',
        'for_or' => 'boolean',
    ];

    public function OrApproved(): Attribute
    {
        return Attribute::make(get: fn() => filled($this->or_number));
    }

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
        return 'CBUBILLING' . '-' . (config('app.transaction_date') ?? today())->format('Y-m-') . str_pad($capitalSubscriptionBilling->id, 6, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::created(function (CapitalSubscriptionBilling $capitalSubscriptionBilling) {
            DB::beginTransaction();
            $capitalSubscriptionBilling->reference_number = $capitalSubscriptionBilling->generateReferenceNumber($capitalSubscriptionBilling);
            CapitalSubscription::query()
                ->where('is_active', true)
                ->withCount('payments')
                ->when($capitalSubscriptionBilling->member_type_id, fn($query, $value) => $query->whereRelation('member', 'member_type_id', $value))
                ->when($capitalSubscriptionBilling->member_subtype_id, fn($query, $value) => $query->whereRelation('member', 'member_subtype_id', $value))
                ->each(function ($cbu) use ($capitalSubscriptionBilling) {
                    $amount_due = $cbu->payments_count > 0 ? $cbu->monthly_payment : $cbu->initial_amount_paid;
                    CapitalSubscriptionBillingPayment::firstOrCreate([
                        'member_id' => $cbu->member_id,
                        'capital_subscription_billing_id' => $capitalSubscriptionBilling->id,
                    ], [
                        'capital_subscription_id' => $cbu->id,
                        'amount_due' => $amount_due,
                        'amount_paid' => $amount_due,
                    ]);
                });
            $capitalSubscriptionBilling->cashier_id = auth()->id();
            $capitalSubscriptionBilling->save();
            DB::commit();
        });
    }
}
