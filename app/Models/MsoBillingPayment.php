<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMsoBillingPayment
 */
class MsoBillingPayment extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function mso_billing()
    {
        return $this->belongsTo(MsoBilling::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
