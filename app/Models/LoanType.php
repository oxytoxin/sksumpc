<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property numeric $minimum_cbu
 * @property numeric $max_amount
 * @property numeric $interest_rate
 * @property numeric $surcharge_rate
 * @property numeric $service_fee
 * @property numeric $cbu_common
 * @property numeric $imprest
 * @property numeric $insurance
 * @property int $has_monthly_amortization
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereCbuCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereHasMonthlyAmortization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereImprest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereMinimumCbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereSurchargeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereUpdatedAt($value)
 * @mixin \Eloquent
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
