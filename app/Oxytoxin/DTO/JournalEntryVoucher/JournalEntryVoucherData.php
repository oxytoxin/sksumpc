<?php

namespace App\Oxytoxin\DTO\JournalEntryVoucher;

use Spatie\LaravelData\Data;

class JournalEntryVoucherData extends Data
{
    public function __construct(
        public string $name,
        public string $address,
        public string $reference_number,
        public string $description,
        public $transaction_date = null
    ) {
        $this->transaction_date ??= today();
    }
}
