<?php

    namespace App\Models;

    use App\Actions\Loans\RunLoanProcessesAfterPosting;
    use App\Actions\Loans\UpdateLoanDeductionsData;
    use App\Enums\LoanTypes;
    use Carbon\CarbonImmutable;
    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Number;
    use NumberFormatter;

    /**
 * @property int $id
 * @property int $loan_account_id
 * @property int|null $loan_buyout_id
 * @property int $member_id
 * @property int $loan_application_id
 * @property int $loan_type_id
 * @property int|null $disbursement_voucher_id
 * @property string $reference_number
 * @property string|null $check_number
 * @property string $priority_number
 * @property numeric $gross_amount
 * @property numeric|null $net_amount
 * @property array<array-key, mixed> $disclosure_sheet_items
 * @property int $number_of_terms
 * @property numeric $interest_rate
 * @property numeric $interest
 * @property numeric $service_fee
 * @property numeric $cbu_amount
 * @property numeric $imprest_amount
 * @property numeric $insurance_amount
 * @property numeric $loan_buyout
 * @property numeric $deductions_amount
 * @property numeric $monthly_payment
 * @property numeric $outstanding_balance
 * @property CarbonImmutable $release_date
 * @property CarbonImmutable $transaction_date
 * @property bool $posted
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read \App\Models\DisbursementVoucher|null $disbursement_voucher
 * @property-read mixed $deductions_list
 * @property-read mixed $maturity_date
 * @property-read mixed $last_payment_before_transaction_date
 * @property-read \App\Models\LoanPayment|null $last_payment
 * @property-read \App\Models\LoanAccount $loan_account
 * @property-read \App\Models\LoanApplication $loan_application
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\Member $member
 * @property-read mixed $net_amount_in_words
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan payable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan posted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereCbuAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereDeductionsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereDisclosureSheetItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereGrossAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereImprestAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanBuyout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanBuyoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan wherePriorityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
    class Loan extends Model
    {
        use HasFactory;

        protected $casts = [
            'original_amount' => 'boolean',
            'gross_amount' => 'decimal:4',
            'deductions_amount' => 'decimal:4',
            'net_amount' => 'decimal:4',
            'disclosure_sheet_items' => 'array',
            'number_of_terms' => 'integer',
            'interest' => 'decimal:4',
            'service_fee' => 'decimal:4',
            'cbu_amount' => 'decimal:4',
            'imprest_amount' => 'decimal:4',
            'insurance_amount' => 'decimal:4',
            'loan_buyout' => 'decimal:4',
            'interest_rate' => 'decimal:4',
            'monthly_payment' => 'decimal:4',
            'release_date' => 'immutable_date',
            'transaction_date' => 'immutable_date',
            'posted' => 'boolean',
        ];

        public function netAmountInWords(): Attribute
        {
            return Attribute::make(get: fn() => (new NumberFormatter('en', NumberFormatter::SPELLOUT))->format($this->net_amount));
        }

        public function disbursement_voucher()
        {
            return $this->belongsTo(DisbursementVoucher::class);
        }

        public function loan_account()
        {
            return $this->belongsTo(LoanAccount::class);
        }

        public function member(): BelongsTo
        {
            return $this->belongsTo(Member::class);
        }

        public function getMonthlyPaymentAttribute($value)
        {
            if ($this->loan_type_id == LoanTypes::SPECIAL_LOAN->value) {
                return 0;
            }

            return $value;
        }

        public function getDeductionsListAttribute()
        {
            return collect($this->deductions)->map(fn($d) => $d['name'].': '.Number::currency($d['amount'], 'PHP'))->toArray();
        }

        public function getMaturityDateAttribute()
        {
            if ($this->loan_type->code == 'SL') {
                if ($this->release_date > CarbonImmutable::create($this->release_date->year, 11, 20)) {
                    return CarbonImmutable::create($this->release_date->year + 1, 5, 20);
                } elseif ($this->release_date > CarbonImmutable::create($this->release_date->year, 5, 20)) {
                    return CarbonImmutable::create($this->release_date->year, 11, 20);
                } else {
                    return CarbonImmutable::create($this->release_date->year, 5, 20);
                }
            }

            return $this->transaction_date->addMonthsNoOverflow($this->number_of_terms);
        }

        public function loan_type(): BelongsTo
        {
            return $this->belongsTo(LoanType::class);
        }

        public function payments(): HasMany
        {
            return $this->hasMany(LoanPayment::class);
        }

        public function last_payment(): HasOne
        {
            return $this->hasOne(LoanPayment::class)->latestOfMany('transaction_date');
        }

        public function lastPaymentBeforeTransactionDate(): Attribute
        {
            return Attribute::make(get: fn() => $this->payments()->where('transaction_date', '<', config('app.transaction_date'))->latest('transaction_date')->first());
        }

        public function scopePending(Builder $query)
        {
            return $query->wherePosted(false);
        }

        public function scopePosted(Builder $query)
        {
            return $query->wherePosted(true);
        }

        public function scopePayable(Builder $query)
        {
            return $query->wherePosted(true)->where('outstanding_balance', '>', 0);
        }

        public function loan_application()
        {
            return $this->belongsTo(LoanApplication::class);
        }

        protected static function booted(): void
        {

            static::saving(function (Loan $loan) {
                if ($loan->posted) {
                    app(RunLoanProcessesAfterPosting::class)->handle($loan);
                } else {
                    $loan = app(UpdateLoanDeductionsData::class)->handle($loan);
                }
            });
        }
    }
