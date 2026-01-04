<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $capital_subscription_id
 * @property int $member_id
 * @property int $payment_type_id
 * @property numeric $amount
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property numeric $running_balance
 * @property string $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereRunningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereWithdrawal($value)
 * @mixin \Eloquent
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
