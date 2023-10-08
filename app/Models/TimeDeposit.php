<?php

namespace App\Models;

use App\Oxytoxin\TimeDepositsProvider;
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
        'amount' => 'decimal:2',
        'number_of_days' => 'integer',
        'maturity_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'transaction_date' => 'immutable_date',
        'maturity_date' => 'immutable_date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    protected static function booted()
    {
        static::creating(function (TimeDeposit $td) {
            $td->interest_rate = TimeDepositsProvider::getInterestRate($td->amount);
            $td->number_of_days = TimeDepositsProvider::NUMBER_OF_DAYS;
            $td->maturity_amount = TimeDepositsProvider::getMaturityAmount($td->amount);
        });
    }
}
