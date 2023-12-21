<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLoanBillingPayment
 */
class LoanBillingPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'posted' => 'boolean',
        'amount_due' => 'decimal:4',
        'amount_paid' => 'decimal:4',
    ];

    public function loan_billing()
    {
        return $this->belongsTo(LoanBilling::class);
    }

    public function loan_amortization()
    {
        return $this->belongsTo(LoanAmortization::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
