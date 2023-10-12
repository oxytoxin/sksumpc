<?php

namespace App\Models;

use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use App\Oxytoxin\ShareCapitalProvider;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;
use function Filament\Support\format_money;

/**
 * @mixin IdeHelperLoan
 */
class Loan extends Model
{
    use HasFactory;

    protected $casts = [
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
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function getDeductionsListAttribute()
    {
        return collect($this->deductions)->map(fn ($d) => $d['name'] . ': ' . format_money($d['amount'], 'PHP'))->toArray();
    }

    public function loan_type(): BelongsTo
    {
        return $this->belongsTo(LoanType::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    protected static function booted(): void
    {
        static::created(function (Loan $loan) {
            $loan->payments()->create([
                'type' => 'JV',
                'reference_number' => '#ORIGINALAMOUNT',
                'amount' => 0,
                'transaction_date' => $loan->transaction_date,
            ]);
        });

        static::creating(function (Loan $loan) {
            $loan->outstanding_balance = $loan->gross_amount + $loan->interest;
            $loan->deductions_amount = collect($loan->deductions)->sum('amount');
            DB::beginTransaction();
            $cbu_amount = collect($loan->deductions)->firstWhere('code', 'cbu_common')['amount'];
            $cbu = $loan->member->capital_subscriptions()->create([
                'number_of_terms' => 0,
                'number_of_shares' => $cbu_amount / ShareCapitalProvider::PAR_VALUE,
                'amount_subscribed' => $cbu_amount,
                'initial_amount_paid' => $cbu_amount,
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
        });
    }
}
