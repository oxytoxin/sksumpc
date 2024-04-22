<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashCollectibleBillingPayment extends Model
{
    use HasFactory;

    public function cash_collectible()
    {
        return $this->belongsTo(CashCollectible::class);
    }

    public function cash_collectible_billing()
    {
        return $this->belongsTo(CashCollectibleBilling::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
