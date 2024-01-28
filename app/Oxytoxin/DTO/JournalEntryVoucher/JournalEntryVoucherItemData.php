<?php

namespace App\Oxytoxin\DTO\JournalEntryVoucher;

use Spatie\LaravelData\Data;

class JournalEntryVoucherItemData extends Data
{
    public function __construct(
        public int $journal_entry_voucher_id,
        public int $trial_balance_entry_id,
        public ?string $debit = null,
        public ?string $credit = null,
    ) {
    }
}
