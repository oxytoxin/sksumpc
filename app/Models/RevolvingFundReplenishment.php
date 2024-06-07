<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevolvingFundReplenishment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'transaction_date' => 'immutable_date',
        'transaction_datetime' => 'immutable_datetime',
    ];
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function booted()
    {
        static::creating(function (RevolvingFundReplenishment $revolving_fund_replenishment) {
            $revolving_fund_replenishment->cashier_id = auth()->id();
        });
    }
}
