<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDisbursementVoucher
 */
class DisbursementVoucher extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_date' => 'immutable_date'
    ];

    public function disbursement_voucher_items()
    {
        return $this->hasMany(DisbursementVoucherItem::class);
    }

    protected static function booted()
    {
        static::creating(function (DisbursementVoucher $disbursementVoucher) {
            $disbursementVoucher->bookkeeper_id = auth()->id();
        });
    }
}
