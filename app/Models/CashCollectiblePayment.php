<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cash_collectible_id
 * @property int|null $member_id
 * @property string $payee
 * @property int $payment_type_id
 * @property string $reference_number
 * @property numeric $amount
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectible $cash_collectible
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereCashCollectibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CashCollectiblePayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'transaction_date' => 'immutable_date',
    ];

    public function cash_collectible()
    {
        return $this->belongsTo(CashCollectible::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected static function booted()
    {
        static::creating(function (CashCollectiblePayment $ccp) {
            $ccp->cashier_id = auth()->id();
        });
    }
}
