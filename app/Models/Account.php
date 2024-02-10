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
    use HasFactory, CreatesChildren, HasRecursiveRelationships;

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
