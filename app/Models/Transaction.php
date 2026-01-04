<?php

namespace App\Models;

use App\Enums\CashEquivalentsTag;
use App\Enums\MsoTransactionTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $transaction_type_id
 * @property int $account_id
 * @property int|null $member_id
 * @property int|null $payment_type_id
 * @property string $reference_number
 * @property string $payee
 * @property string|null $remarks
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string|null $tag
 * @property int|null $from_billing_type
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\TransactionType $transaction_type
 * @method static Builder<static>|Transaction newModelQuery()
 * @method static Builder<static>|Transaction newQuery()
 * @method static Builder<static>|Transaction query()
 * @method static Builder<static>|Transaction whereAccountId($value)
 * @method static Builder<static>|Transaction whereCreatedAt($value)
 * @method static Builder<static>|Transaction whereCredit($value)
 * @method static Builder<static>|Transaction whereDebit($value)
 * @method static Builder<static>|Transaction whereFromBillingType($value)
 * @method static Builder<static>|Transaction whereId($value)
 * @method static Builder<static>|Transaction whereMemberId($value)
 * @method static Builder<static>|Transaction wherePayee($value)
 * @method static Builder<static>|Transaction wherePaymentTypeId($value)
 * @method static Builder<static>|Transaction whereReferenceNumber($value)
 * @method static Builder<static>|Transaction whereRemarks($value)
 * @method static Builder<static>|Transaction whereTag($value)
 * @method static Builder<static>|Transaction whereTransactionDate($value)
 * @method static Builder<static>|Transaction whereTransactionTypeId($value)
 * @method static Builder<static>|Transaction whereUpdatedAt($value)
 * @method static Builder<static>|Transaction withoutCashEquivalents()
 * @method static Builder<static>|Transaction withoutMso()
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
        'transaction_date' => 'immutable_datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function scopeWithoutMso(Builder $query)
    {
        $query->where(function ($query) {
            $query->whereNotIn('tag', MsoTransactionTag::get())
                ->orWhereNull('tag');
        });
    }

    public function scopeWithoutCashEquivalents(Builder $query)
    {
        $query->where(function ($query) {
            $query->whereNotIn('tag', CashEquivalentsTag::get())
                ->orWhereNull('tag');
        });
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            if (blank($transaction->payee)) {
                $transaction->payee = $transaction->member?->full_name ?? 'SKSU-MPC';
            }
        });
    }
}
