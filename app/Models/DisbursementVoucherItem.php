<?php

namespace App\Models;

use App\Observers\DisbursementVoucherItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(DisbursementVoucherItemObserver::class)]
/**
 * @property int $id
 * @property int $disbursement_voucher_id
 * @property int $account_id
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property string $transaction_date
 * @property array<array-key, mixed> $details
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\DisbursementVoucher $disbursement_voucher
 * @property-read \App\Models\Account $item_account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereUpdatedAt($value)
 * @mixin \Eloquent
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
