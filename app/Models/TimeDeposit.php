<?php

namespace App\Models;

use App\Oxytoxin\Providers\TimeDepositsProvider;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NumberFormatter;

/**
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string|null $identifier
 * @property \Carbon\CarbonImmutable $maturity_date
 * @property string|null $withdrawal_date
 * @property numeric $amount
 * @property int $number_of_days
 * @property numeric $maturity_amount
 * @property numeric $interest_rate
 * @property numeric|null $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string $tdc_number
 * @property string $time_deposit_account_id
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $accrued_interest
 * @property-read mixed $amount_in_words
 * @property-read \App\Models\User|null $cashier
 * @property-read mixed $days_in_words
 * @property-read mixed $interest_earned
 * @property-read mixed $interest_rate_in_words
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\TimeDepositAccount|null $time_deposit_account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereMaturityAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereMaturityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereTdcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereTimeDepositAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereWithdrawalDate($value)
 * @mixin \Eloquent
 */
class TimeDeposit extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'number_of_days' => 'integer',
        'maturity_amount' => 'decimal:4',
        'interest_rate' => 'decimal:4',
        'transaction_date' => 'immutable_date',
        'maturity_date' => 'immutable_date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function time_deposit_account()
    {
        return $this->belongsTo(TimeDepositAccount::class);
    }

    public function amountInWords(): Attribute
    {
        return Attribute::make(get: fn () => (new NumberFormatter('en', NumberFormatter::SPELLOUT))->format($this->amount));
    }

    public function daysInWords(): Attribute
    {
        return Attribute::make(get: fn () => (new NumberFormatter('en', NumberFormatter::SPELLOUT))->format($this->number_of_days));
    }

    public function interestRateInWords(): Attribute
    {
        return Attribute::make(get: fn () => (new NumberFormatter('en', NumberFormatter::SPELLOUT))->format(round($this->interest_rate * 100, 2)));
    }

    public function accruedInterest(): Attribute
    {
        $interest = TimeDepositsProvider::getMaturityAmount(
            amount: $this->amount,
            interest_rate: TimeDepositsProvider::TERMINATION_INTEREST_RATE,
            number_of_days: $this->transaction_date?->diffInDays(config('app.transaction_date') ?? today())
        ) - $this->amount;

        return Attribute::make(get: fn () => round($interest, 2));
    }

    public function interestEarned(): Attribute
    {
        $days = $this->transaction_date?->diffInDays(config('app.transaction_date') ?? today());
        $interest = TimeDepositsProvider::getMaturityAmount($this->amount, $this->interest_rate, $days) - $this->amount;

        return Attribute::make(get: fn () => round($interest, 2));
    }

    protected static function booted()
    {
        static::creating(function (TimeDeposit $td) {
            $td->cashier_id = auth()->id();
            $td->interest_rate ??= TimeDepositsProvider::getInterestRate($td->amount);
            $td->tdc_number = str('TDC-')->append(str_pad((TimeDeposit::latest('id')->first()->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
            $td->number_of_days ??= TimeDepositsProvider::NUMBER_OF_DAYS;
            $td->maturity_amount ??= TimeDepositsProvider::getMaturityAmount($td->amount);
        });
    }
}
