<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCashCollectiblePayment
 */
class CashCollectiblePayment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'transaction_date' => 'immutable_date'
    ];

    public function cash_collectible()
    {
        return $this->belongsTo(CashCollectible::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    protected static function booted()
    {
        static::creating(function (CashCollectiblePayment $ccp) {
            $ccp->cashier_id = auth()->id();
        });
    }
}
