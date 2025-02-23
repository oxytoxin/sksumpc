<?php

namespace App\Models;

use App\Observers\DisbursementVoucherItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(DisbursementVoucherItemObserver::class)]
/**
 * @mixin IdeHelperDisbursementVoucherItem
 */
class DisbursementVoucherItem extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
        'details' => 'array',
    ];

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function item_account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->tree();
    }
}
