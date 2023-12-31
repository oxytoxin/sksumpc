<?php

namespace App\Models;

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
        'amount' => 'decimal:4',
        'interest' => 'decimal:4',
        'principal_payment' => 'decimal:4',
        'transaction_date' => 'immutable_date',
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
            $principal_payment = 0;
            while ($amount_paid > 0) {
                $active_loan_amortization = $loan->active_loan_amortization;
                if (! $active_loan_amortization) {
                    break;
                }
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
                $active_loan_amortization->refresh();
                $amount_paid -= $amount;
                $principal_payment += $active_loan_amortization->principal_payment;
                $loan->load('active_loan_amortization');
            }
            $loanPayment->principal_payment = $principal_payment;
            $loanPayment->cashier_id = auth()->id();
            DB::commit();
        });
    }
}
