<?php

namespace App\Models;

use App\Oxytoxin\LoansProvider;
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
        'interest' => 'decimal:2',
        'principal' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'transaction_date' => 'immutable_date'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted(): void
    {
        static::creating(function (LoanPayment $loanPayment) {
            $loan = $loanPayment->loan;
            $days = $loanPayment->transaction_date->diffInDays($loan->last_payment?->transaction_date ?? $loan->transaction_date);
            $interest = $loan->interest_rate * $loan->outstanding_balance * ($days / LoansProvider::DAYS_IN_MONTH);
            $loanPayment->interest = $interest;
            $loanPayment->principal = $loanPayment->amount - $interest;
            $loanPayment->running_balance = $loanPayment->loan->outstanding_balance - $loanPayment->principal;
            $loanPayment->cashier_id = auth()->id();
        });
    }
}
