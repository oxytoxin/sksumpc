<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCashCollectibleBilling
 */
class CashCollectibleBilling extends Model
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
        return Attribute::make(get: fn () => filled($this->or_number));
    }

    public function generateReferenceNumber(self $cashCollectionBilling)
    {
        return 'STAKEHOLDERSBILLING'.'-'.(config('app.transaction_date') ?? today())->format('Y-m-').str_pad($cashCollectionBilling->id, 6, '0', STR_PAD_LEFT);
    }

    public function cash_collectible_account()
    {
        return $this->belongsTo(CashCollectibleAccount::class, 'account_id');
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
