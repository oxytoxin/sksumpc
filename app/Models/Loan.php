<?php

namespace App\Models;

use App\Actions\Loans\RunLoanProcessesAfterPosting;
use App\Actions\Loans\UpdateLoanDeductionsData;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use function Filament\Support\format_money;

/**
 * @mixin IdeHelperLoan
 */
class Loan extends Model
{
    use HasFactory;

    protected $casts = [
        'original_amount' => 'boolean',
        'gross_amount' => 'decimal:4',
        'deductions_amount' => 'decimal:4',
        'net_amount' => 'decimal:4',
        'deductions' => 'array',
        'number_of_terms' => 'integer',
        'interest' => 'decimal:4',
        'service_fee' => 'decimal:4',
        'cbu_amount' => 'decimal:4',
        'imprest_amount' => 'decimal:4',
        'insurance_amount' => 'decimal:4',
        'loan_buyout_interest' => 'decimal:4',
        'loan_buyout_principal' => 'decimal:4',
        'interest_rate' => 'decimal:4',
        'monthly_payment' => 'decimal:4',
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
        return $this->hasOne(LoanPayment::class)->latestOfMany('transaction_date');
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

    public function loan_application()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    protected static function booted(): void
    {

        static::saving(function (Loan $loan) {
            $loan = UpdateLoanDeductionsData::run($loan);
            if ($loan->posted) {
                RunLoanProcessesAfterPosting::run($loan);
            }
        });
    }
}
