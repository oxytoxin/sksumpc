<?php

namespace App\Models;

use App\Oxytoxin\Providers\TimeDepositsProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTimeDeposit
 */
class TimeDeposit extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'number_of_days' => 'integer',
        'maturity_amount' => 'decimal:4',
        'interest_rate' => 'decimal:4',
        'transaction_date' => 'immutable_date',
        'maturity_date' => 'immutable_date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted()
    {
        static::creating(function (TimeDeposit $td) {
            $td->cashier_id = auth()->id();
            $td->interest_rate = TimeDepositsProvider::getInterestRate($td->amount);
            $td->number_of_days = TimeDepositsProvider::NUMBER_OF_DAYS;
            $td->maturity_amount = TimeDepositsProvider::getMaturityAmount($td->amount);
        });
    }
}
