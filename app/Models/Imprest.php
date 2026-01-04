<?php

    namespace App\Models;

    use App\Oxytoxin\Providers\ImprestsProvider;
    use App\Oxytoxin\Providers\TimeDepositsProvider;
    use DB;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * @property int $id
     * @property int $member_id
     * @property int $payment_type_id
     * @property string $reference_number
     * @property numeric $amount
     * @property numeric|null $deposit
     * @property numeric|null $withdrawal
     * @property numeric $interest_rate
     * @property numeric $interest
     * @property \Carbon\CarbonImmutable $transaction_date
     * @property \Carbon\CarbonImmutable|null $transaction_datetime
     * @property \Carbon\CarbonImmutable|null $interest_date
     * @property numeric $balance
     * @property bool $accrued
     * @property int|null $cashier_id
     * @property \Carbon\CarbonImmutable|null $created_at
     * @property \Carbon\CarbonImmutable|null $updated_at
     * @property-read \App\Models\Account|null $account
     * @property-read \App\Models\User|null $cashier
     * @property-read \App\Models\Member $member
     * @property-read \App\Models\RevolvingFund|null $revolving_fund
     * @method static Builder<static>|Imprest newModelQuery()
     * @method static Builder<static>|Imprest newQuery()
     * @method static Builder<static>|Imprest query()
     * @method static Builder<static>|Imprest whereAccrued($value)
     * @method static Builder<static>|Imprest whereAmount($value)
     * @method static Builder<static>|Imprest whereBalance($value)
     * @method static Builder<static>|Imprest whereCashierId($value)
     * @method static Builder<static>|Imprest whereCreatedAt($value)
     * @method static Builder<static>|Imprest whereDeposit($value)
     * @method static Builder<static>|Imprest whereId($value)
     * @method static Builder<static>|Imprest whereInterest($value)
     * @method static Builder<static>|Imprest whereInterestDate($value)
     * @method static Builder<static>|Imprest whereInterestRate($value)
     * @method static Builder<static>|Imprest whereMemberId($value)
     * @method static Builder<static>|Imprest wherePaymentTypeId($value)
     * @method static Builder<static>|Imprest whereReferenceNumber($value)
     * @method static Builder<static>|Imprest whereTransactionDate($value)
     * @method static Builder<static>|Imprest whereTransactionDatetime($value)
     * @method static Builder<static>|Imprest whereUpdatedAt($value)
     * @method static Builder<static>|Imprest whereWithdrawal($value)
     * @mixin \Eloquent
     */
    class Imprest extends Model
    {
        use HasFactory;

        protected $casts = [
            'amount' => 'decimal:4',
            'deposit' => 'decimal:4',
            'withdrawal' => 'decimal:4',
            'number_of_days' => 'integer',
            'interest_rate' => 'decimal:4',
            'interest' => 'decimal:4',
            'accrued' => 'boolean',
            'transaction_date' => 'immutable_date',
            'interest_date' => 'immutable_date',
            'transaction_datetime' => 'immutable_datetime',
        ];

        public function member(): BelongsTo
        {
            return $this->belongsTo(Member::class);
        }

        public function cashier()
        {
            return $this->belongsTo(User::class, 'cashier_id');
        }

        public function account()
        {
            return $this->belongsTo(Account::class);
        }

        protected static function booted()
        {
            static::addGlobalScope(function (Builder $q) {
                return $q->addSelect(DB::raw("
                *, 
                DATEDIFF(COALESCE(LEAD(transaction_date) OVER (ORDER BY transaction_date), '".(config('app.transaction_date') ?? today())->format('Y-m-d')."'), transaction_date) as days_till_next_transaction,
                DATEDIFF(transaction_date, COALESCE(LAG(transaction_date) OVER (ORDER BY transaction_date), transaction_date)) as days_since_last_transaction
            "));
            });

            static::creating(function (Imprest $imprest) {
                $imprest->cashier_id = auth()->id();
            });

            Imprest::created(function (Imprest $imprest) {
                $prefix = match ($imprest->reference_number) {
                    ImprestsProvider::FROM_TRANSFER_CODE => 'IT-',
                    ImprestsProvider::WITHDRAWAL_TRANSFER_CODE => 'IW-',
                    ImprestsProvider::DEPOSIT_TRANSFER_CODE => 'ID-',
                    TimeDepositsProvider::FROM_TRANSFER_CODE => 'TD-',
                    default => null
                };

                if ($prefix) {
                    $imprest->reference_number = str($prefix)->append(today()->format('Y').'-')->append(str_pad($imprest->id, 6, '0', STR_PAD_LEFT));
                }

                $imprest->save();
            });
        }

        public function revolving_fund()
        {
            return $this->morphOne(RevolvingFund::class, 'withdrawable');
        }
    }
