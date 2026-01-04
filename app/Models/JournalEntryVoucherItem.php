<?php

namespace App\Models;

use App\Observers\JournalEntryVoucherItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(JournalEntryVoucherItemObserver::class)]
/**
 * @property int $id
 * @property int $journal_entry_voucher_id
 * @property int $account_id
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property array<array-key, mixed> $details
 * @property string $transaction_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\JournalEntryVoucher $journal_entry_voucher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereJournalEntryVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereUpdatedAt($value)
 * @mixin \Eloquent
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
