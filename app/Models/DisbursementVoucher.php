<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $voucher_type_id
 * @property string $name
 * @property string|null $address
 * @property string|null $check_number
 * @property string $reference_number
 * @property string $voucher_number
 * @property string $description
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $bookkeeper_id
 * @property int $is_legacy
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucherItem> $disbursement_voucher_items
 * @property-read int|null $disbursement_voucher_items_count
 * @property-read \App\Models\VoucherType $voucher_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereBookkeeperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereIsLegacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereVoucherNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereVoucherTypeId($value)
 * @mixin \Eloquent
 */
class DisbursementVoucher extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_date' => 'immutable_date',
    ];

    public function voucher_type()
    {
        return $this->belongsTo(VoucherType::class);
    }

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
