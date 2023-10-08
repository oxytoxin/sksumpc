<?php

namespace App\Models;

use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        static::creating(function (Loan $loan) {
            $loan->outstanding_balance = $loan->gross_amount + $loan->interest;
            DB::beginTransaction();
            $loan->member->capital_subscriptions_common->payments()->create([
                'type' => 'JV',
                'reference_number' => '#LOANCBU',
                'amount' => collect($loan->deductions)->firstWhere('name', 'CBU-Common')['amount'],
                'transaction_date' => today(),
            ]);
            ImprestsProvider::createImprest($loan->member, (new ImprestData(
                transaction_date: today(),
                reference_number: '#LOANIMPREST',
                amount: collect($loan->deductions)->firstWhere('name', 'Imprest Savings')['amount'],
            )));
            DB::commit();
        });
    }
}
