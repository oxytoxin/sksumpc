<?php

namespace App\Models;

use App\Observers\JournalEntryVoucherItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(JournalEntryVoucherItemObserver::class)]
/**
 * @mixin IdeHelperJournalEntryVoucherItem
 */
class JournalEntryVoucherItem extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
        'details' => 'array',
    ];

    public function journal_entry_voucher()
    {
        return $this->belongsTo(JournalEntryVoucher::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->tree();
    }
}
