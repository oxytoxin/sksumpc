<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCashCollectibleSubscription
 */
class CashCollectibleSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'billable_amount' => 'decimal:4',
    ];

    public function cash_collectible_account()
    {
        return $this->belongsTo(CashCollectibleAccount::class, 'account_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
