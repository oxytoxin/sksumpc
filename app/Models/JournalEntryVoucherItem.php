<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperJournalEntryVoucherItem
 */
class JournalEntryVoucherItem extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
    ];

    public function journal_entry_voucher()
    {
        return $this->belongsTo(JournalEntryVoucher::class);
    }

    public function trial_balance_entry()
    {
        return $this->belongsTo(TrialBalanceEntry::class);
    }
}
