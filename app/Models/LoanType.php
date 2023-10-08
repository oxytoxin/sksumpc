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

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
