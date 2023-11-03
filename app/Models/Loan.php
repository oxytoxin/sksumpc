<?php

namespace App\Models;

use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use App\Oxytoxin\LoansProvider;
use App\Oxytoxin\ShareCapitalProvider;
use DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Str;
use function Filament\Support\format_money;

/**
 * @mixin IdeHelperLoan
 */
class Loan extends Model
{
    use HasFactory;

    protected $casts = [
        'original_amount' => 'boolean',
        'gross_amount' => 'decimal:2',
        'deductions_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'deductions' => 'array',
        'number_of_terms' => 'integer',
        'interest' => 'decimal:2',
        'interest_rate' => 'decimal:4',
        'monthly_payment' => 'decimal:2',
        'release_date' => 'immutable_date',
        'transaction_date' => 'immutable_date',
        'posted' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function getDeductionsListAttribute()
    {
        return collect($this->deductions)->map(fn ($d) => $d['name'] . ': ' . format_money($d['amount'], 'PHP'))->toArray();
    }

    public function getMaturityDateAttribute()
    {
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
        return $this->hasOne(LoanPayment::class)->latestOfMany();
    }

    public function scopePending(Builder $query)
    {
        return $query->wherePosted(false);
    }

    public function scopePosted(Builder $query)
    {
        return $query->wherePosted(true);
    }

    public function loan_amortizations()
    {
        return $this->hasMany(LoanAmortization::class);
    }

    public function paid_loan_amortizations()
    {
        return $this->hasMany(LoanAmortization::class)->whereNotNull('amount_paid');
    }

    public function active_loan_amortization()
    {
        return $this->hasOne(LoanAmortization::class)->whereNull('amount_paid')->orWhere('arrears', '>', 0);
    }

    protected static function booted(): void
    {

        static::saving(function (Loan $loan) {
            $loan->outstanding_balance = $loan->gross_amount;
            $loan->deductions_amount = collect($loan->deductions)->sum('amount');

            if ($loan->posted) {
                DB::beginTransaction();
                $amortization_schedule = LoansProvider::generateAmortizationSchedule($loan);
                $loan->loan_amortizations()->createMany($amortization_schedule);
                $cbu_amount = collect($loan->deductions)->firstWhere('code', 'cbu_common')['amount'];
                $cbu = $loan->member->capital_subscriptions()->create([
                    'number_of_terms' => 0,
                    'number_of_shares' => $cbu_amount / ShareCapitalProvider::PAR_VALUE,
                    'amount_subscribed' => $cbu_amount,
                    'par_value' => ShareCapitalProvider::PAR_VALUE,
                    'is_common' => false,
                    'code' => Str::random(12),
                    'transaction_date' => $loan->transaction_date
                ]);
                $cbu->payments()->create([
                    'type' => 'JV',
                    'reference_number' => $loan->reference_number,
                    'amount' => $cbu_amount,
                    'transaction_date' => $loan->transaction_date,
                ]);
                ImprestsProvider::createImprest($loan->member, (new ImprestData(
                    transaction_date: $loan->transaction_date,
                    type: 'OR',
                    reference_number: $loan->reference_number,
                    amount: collect($loan->deductions)->firstWhere('code', 'imprest')['amount'],
                )));
                $buyOut = collect($loan->deductions)->firstWhere('code', 'buy_out');
                if ($buyOut) {
                    $existing = $loan->member->loans()->find($buyOut['loan_id']);
                    $existing?->payments()->create([
                        'type' => 'JV',
                        'reference_number' => $loan->reference_number,
                        'amount' => $buyOut['amount'],
                        'transaction_date' => $loan->transaction_date,
                    ]);
                }


                DB::commit();
            }
        });
    }
}
