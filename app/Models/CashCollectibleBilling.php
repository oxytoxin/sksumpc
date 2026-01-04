<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property int $account_id
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property string|null $reference_number
 * @property string|null $name
 * @property string|null $or_number
 * @property \Carbon\CarbonImmutable|null $or_date
 * @property int|null $cashier_id
 * @property bool $posted
 * @property bool $for_or
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $or_approved
 * @property-read \App\Models\CashCollectibleAccount $cash_collectible_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectibleBillingPayment> $cash_collectible_billing_payments
 * @property-read int|null $cash_collectible_billing_payments_count
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\PaymentType|null $payment_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereForOr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereOrDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereOrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereUpdatedAt($value)
 * @mixin \Eloquent
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
