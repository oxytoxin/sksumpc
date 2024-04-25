<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashCollectibleBilling extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'posted' => 'boolean',
        'for_or' => 'boolean',
    ];

    public function generateReferenceNumber(self $cashCollectionBilling)
    {
        return 'STAKEHOLDERSBILLING' . '-' . today()->format('Y-m-') . str_pad($cashCollectionBilling->id, 6, '0', STR_PAD_LEFT);
    }

    public function cash_collectible()
    {
        return $this->belongsTo(CashCollectible::class);
    }

    public function cash_collectible_billing_payments()
    {
        return $this->hasMany(CashCollectibleBillingPayment::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted(): void
    {
        static::created(function (CashCollectibleBilling $cashCollectibleBilling) {
            $cashCollectibleBilling->reference_number = $cashCollectibleBilling->generateReferenceNumber($cashCollectibleBilling);
            $cashCollectibleBilling->cashier_id = auth()->id();
            $cashCollectibleBilling->save();
        });
    }
}
