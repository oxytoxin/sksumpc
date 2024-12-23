<?php

namespace App\Models;

use App\Oxytoxin\Traits\CreatesChildren;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', 1);
    }

    public function recursiveCdjTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', 2);
    }

    public function recursiveJevTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', 3);
    }
}
