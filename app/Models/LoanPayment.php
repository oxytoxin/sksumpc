<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLoanPayment
 */
class LoanPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'transaction_date' => 'immutable_date'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    protected static function booted(): void
    {
        static::creating(function (LoanPayment $loanPayment) {
            $loanPayment->running_balance = $loanPayment->loan->outstanding_balance - $loanPayment->amount;
        });
    }
}
