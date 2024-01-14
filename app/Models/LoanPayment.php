<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Builder;
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
        'buy_out' => 'boolean',
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
            $loanPayment->cashier_id = auth()->id();
        });
    }
}
