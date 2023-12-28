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
            TrialBalanceEntry::create([
                'name' => strtolower($loanType->name),
                'auditable_type' => LoanType::class,
                'auditable_id' => $loanType->id,
            ])->insertBeforeNode(
                TrialBalanceEntry::firstWhere('name', 'allowance for probable losses-loans')
            );
            TrialBalanceEntry::create([
                'name' => strtolower($loanType->name),
                'auditable_type' => LoanType::class,
                'auditable_id' => $loanType->id,
            ])->insertBeforeNode(
                TrialBalanceEntry::firstWhere('name', 'service fee-loans')
            );
        });
    }
}
