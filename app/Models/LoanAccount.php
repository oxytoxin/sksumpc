<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperLoanAccount
 */
class LoanAccount extends Account
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_loans_receivable', function ($query) {
            $query->whereNotNull('member_id')->whereTag('member_loans_receivable');
        });
    }

    public function loan()
    {
        return $this->hasOne(Loan::class);
    }
}
