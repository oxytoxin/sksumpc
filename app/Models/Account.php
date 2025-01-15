<?php

namespace App\Models;

use DB;
use App\Enums\TransactionTypes;
use Illuminate\Database\Eloquent\Model;
use App\Oxytoxin\Traits\CreatesChildren;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * @mixin IdeHelperAccount
 */
class Account extends Model
{
    use CreatesChildren, HasFactory, HasRecursiveRelationships;

    protected $table = 'accounts';

    public function getCustomPaths()
    {
        return [
            [
                'name' => 'fullname',
                'column' => 'name',
                'separator' => ':',
            ],
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public static function getCashOnHand()
    {
        return Account::firstWhere('tag', 'cash_on_hand');
    }

    public static function getCashInBankGF()
    {
        return Account::firstWhere('tag', 'cash_in_bank_dbp_gf');
    }

    public static function getCashInBankMSO()
    {
        return Account::firstWhere('tag', 'cash_in_bank_dbp_mso');
    }

    public static function getRevolvingFund()
    {
        return Account::firstWhere('tag', 'revolving_fund');
    }

    public static function getTimeDepositInterestExpense()
    {
        return Account::firstWhere('tag', 'time_deposit_interest_expense');
    }

    public static function getSavingsInterestExpense()
    {
        return Account::firstWhere('tag', 'savings_deposit_interest_expense');
    }

    public static function getMemberTimeDeposits()
    {
        return Account::firstWhere('tag', 'member_time_deposits');
    }

    public static function getLoanReceivable(LoanType $loanType)
    {
        return Account::whereAccountableType(LoanType::class)->whereAccountableId($loanType->id)->whereTag('loan_receivables')->first();
    }

    public static function getServiceFeeLoans()
    {
        return Account::firstWhere('tag', 'service_fee_loans');
    }

    public static function getLoanInsurance()
    {
        return Account::firstWhere('tag', 'insurance_loans');
    }


    public static function getCbuDeposit($member_type_id)
    {
        return match ($member_type_id) {
            1 => Account::firstWhere('tag', 'member_regular_cbu_deposit'),
            2 => Account::firstWhere('tag', 'member_preferred_cbu_deposit'),
            3 => Account::firstWhere('tag', 'member_laboratory_cbu_deposit'),
            default => Account::firstWhere('tag', 'member_regular_cbu_deposit'),
        };
    }

    public function scopeWithCode(Builder $query)
    {
        return $query->tree()->orderBy('id')->addSelect(DB::raw("*,concat(number,' - ', fullname) as code"));
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function recursiveTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class);
    }

    public function recursiveCrjTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', TransactionTypes::CRJ->value);
    }

    public function recursiveCdjTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', TransactionTypes::CDJ->value);
    }

    public function recursiveJevTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', TransactionTypes::JEV->value);
    }
}
