<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLoanBilling
 */
class LoanBilling extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'posted' => 'boolean'
    ];

    public function loan_billing_payments()
    {
        return $this->hasMany(LoanBillingPayment::class);
    }

    public function loan_type()
    {
        return $this->belongsTo(LoanType::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted(): void
    {
        static::created(function (LoanBilling $loanBilling) {
            DB::beginTransaction();
            LoanAmortization::whereRelation('loan', 'loan_type_id', $loanBilling->loan_type_id)->where('billable_date', $loanBilling->date->format('F Y'))->whereNull('amount_paid')->each(function ($la) use ($loanBilling) {
                LoanBillingPayment::firstOrCreate([
                    'member_id' => $la->loan->member_id,
                    'loan_billing_id' => $loanBilling->id,
                    'loan_amortization_id' => $la->id,
                ], [
                    'amount_due' => $la->amortization,
                    'amount_paid' => $la->amortization,
                ]);
            });
            $loanBilling->cashier_id = auth()->id();
            $loanBilling->save();
            DB::commit();
        });
    }
}
