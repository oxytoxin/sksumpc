<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperLoanAccount
 */
class TimeDepositAccount extends Account
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_time_deposits', function ($query) {
            $query->whereNotNull('member_id')->whereTag('member_time_deposits');
        });
    }

    public function time_deposit()
    {
        return $this->hasOne(TimeDeposit::class);
    }
}
