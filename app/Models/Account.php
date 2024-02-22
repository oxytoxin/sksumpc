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

    public static function getCashInBankGF()
    {
        return Account::find(3);
    }

    public static function getLoanReceivable(LoanType $loanType)
    {
        return Account::whereAccountableType(LoanType::class)->whereAccountableId($loanType->id)->whereTag('loan_receivables')->first();
    }

    public static function getServiceFeeLoans()
    {
        return Account::find(75);
    }

    public static function getLoanInsurance()
    {
        return Account::find(65);
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
