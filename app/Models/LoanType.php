<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperLoanType
 */
class LoanType extends Model
{
    use HasFactory;

    protected $casts = [
        'interest_rate' => 'decimal:4',
        'surcharge_rate' => 'decimal:4',
        'interest' => 'decimal:4',
        'service_fee' => 'decimal:4',
        'cbu_common' => 'decimal:4',
        'imprest' => 'decimal:4',
        'insurance' => 'decimal:4',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    protected static function booted()
    {
        static::created(function (LoanType $loanType) {
            $loan_receivables = Account::firstWhere('tag', 'loan_receivables');
            $loan_interests = Account::firstWhere('tag', 'loan_interests');
            Account::create([
                'account_type_id' => $loan_receivables->account_type_id,
                'name' => strtoupper($loanType->name),
                'number' => str($loan_receivables->number)->append('-')->append(mb_str_pad($loanType->id, 4, '0', STR_PAD_LEFT)),
                'accountable_type' => LoanType::class,
                'accountable_id' => $loanType->id,
                'tag' => 'loan_receivables',
            ], $loan_receivables);
            Account::create([
                'account_type_id' => $loan_interests->account_type_id,
                'name' => strtoupper($loanType->name),
                'number' => str($loan_interests->number)->append('-')->append(mb_str_pad($loanType->id, 4, '0', STR_PAD_LEFT)),
                'accountable_type' => LoanType::class,
                'accountable_id' => $loanType->id,
                'tag' => 'loan_interests',
            ], $loan_interests);
        });
    }
}
