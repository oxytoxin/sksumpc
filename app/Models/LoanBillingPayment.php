<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanBillingPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'posted' => 'boolean',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
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
