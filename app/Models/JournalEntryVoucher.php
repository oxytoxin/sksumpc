<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperJournalEntryVoucher
 */
class JournalEntryVoucher extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_date' => 'immutable_date',
    ];

    public function voucher_type()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function journal_entry_voucher_items()
    {
        return $this->hasMany(JournalEntryVoucherItem::class);
    }

    protected static function booted()
    {
        static::creating(function (JournalEntryVoucher $journalEntryVoucher) {
            $journalEntryVoucher->bookkeeper_id = auth()->id();
        });
    }
}
