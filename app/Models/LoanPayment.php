<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property bool $buy_out
 * @property int|null $loan_billing_id
 * @property int $loan_id
 * @property int $member_id
 * @property numeric $amount
 * @property numeric $interest_payment
 * @property numeric $principal_payment
 * @property numeric $unpaid_interest
 * @property numeric $surcharge_payment
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string|null $remarks
 * @property CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User|null $cashier
 * @property-read LoanBilling|null $loan_billing
 * @property-read Loan $loan
 * @property-read Member $member
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereBuyOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereInterestPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment wherePrincipalPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereSurchargePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereUnpaidInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LoanPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:2',
        'interest' => 'decimal:2',
        'interest_payment' => 'decimal:2',
        'principal_payment' => 'decimal:2',
        'unpaid_interest' => 'decimal:2',
        'surcharge_payment' => 'decimal:2',
        'transaction_date' => 'immutable_date',
        'buy_out' => 'boolean',
    ];

    public function loan_billing(): BelongsTo
    {
        return $this->belongsTo(LoanBilling::class);
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted(): void
    {
        static::creating(function (LoanPayment $loanPayment) {
            $loanPayment->cashier_id = auth()->id();
        });
    }

    public function getTransactions(): Collection
    {
        return Transaction::query()
            ->where('reference_number', $this->reference_number)
            ->where('member_id', $this->member_id)
            ->where('transaction_date', $this->transaction_date)
            ->where(function ($query) {
                $query
                    ->when($this->principal_payment, fn ($query) => $query->orWhere(function ($query) {
                        $query->where('credit', $this->principal_payment)
                            ->where('remarks', 'Member Loan Payment Principal');
                    }))
                    ->when($this->interest_payment, fn ($query) => $query->orWhere(function ($query) {
                        $query->where('credit', $this->interest_payment)
                            ->where('remarks', 'Member Loan Payment Interest');
                    }))
                    ->when($this->surcharge_payment, fn ($query) => $query->orWhere(function ($query) {
                        $query->where('credit', $this->surcharge_payment)
                            ->where('remarks', 'Member Loan Payment Surcharge');
                    }))
                    ->orWhere(function ($query) {
                        $query->whereIn('account_id', [2, 4])
                            ->whereNull('credit')
                            ->where('debit', $this->amount);
                    });
            })
            ->get();
    }
}
