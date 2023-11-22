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
        'amortization' => 'decimal:2',
        'interest' => 'decimal:2',
        'principal' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'principal_payment' => 'decimal:2',
        'arrears' => 'decimal:2',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
