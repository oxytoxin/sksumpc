<?php

namespace App\Models;

use App\Oxytoxin\Traits\CreatesChildren;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * @mixin IdeHelperAccount
 */
class Account extends Model
{
    use HasFactory, CreatesChildren, HasRecursiveRelationships;

    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }
}
