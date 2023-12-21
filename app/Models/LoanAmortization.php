<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLoanAmortization
 */
class LoanAmortization extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'amortization' => 'decimal:4',
        'interest' => 'decimal:4',
        'principal' => 'decimal:4',
        'previous_balance' => 'decimal:4',
        'outstanding_balance' => 'decimal:4',
        'amount_paid' => 'decimal:4',
        'principal_payment' => 'decimal:4',
        'interest_payment' => 'decimal:4',
        'arrears' => 'decimal:4',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
