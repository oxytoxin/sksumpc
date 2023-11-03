<?php

namespace App\Models;

use App\Oxytoxin\LoansProvider;
use DB;
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
            DB::beginTransaction();
            $loan = $loanPayment->loan;
            $amount_paid = $loanPayment->amount;
            while ($amount_paid > 0) {
                $active_loan_amortization = $loan->active_loan_amortization;
                if ($active_loan_amortization->arrears > 0) {
                    $amount = $amount_paid > $active_loan_amortization->arrears ? $active_loan_amortization->arrears : $amount_paid;
                    $active_loan_amortization->update([
                        'amount_paid' => $active_loan_amortization->amount_paid + $amount,
                    ]);
                } else {
                    $amount = $amount_paid > $active_loan_amortization->amortization ? $active_loan_amortization->amortization : $amount_paid;
                    $active_loan_amortization->update([
                        'amount_paid' => $amount,
                    ]);
                }

                $amount_paid -= $amount;
                $loan->load('active_loan_amortization');
            }

            $days = $loanPayment->transaction_date->diffInDays($loan->last_payment?->transaction_date ?? $loan->transaction_date);
            $interest = $loan->interest_rate * $loan->outstanding_balance * ($days / LoansProvider::DAYS_IN_MONTH);
            $loanPayment->interest = $interest;
            $loanPayment->principal = $loanPayment->amount - $interest;
            $loanPayment->running_balance = $loanPayment->loan->outstanding_balance - $loanPayment->principal;
            $loanPayment->cashier_id = auth()->id();
            DB::commit();
        });
    }
}
