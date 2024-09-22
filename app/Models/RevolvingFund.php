<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RevolvingFund extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_date' => 'immutable_datetime',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function withdrawable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::creating(function (RevolvingFund $revolving_fund) {
            $revolving_fund->cashier_id = auth()->id();
        });
    }

}
