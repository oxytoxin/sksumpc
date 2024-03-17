<?php

namespace App\Oxytoxin\DTO\Transactions;

use App\Models\TransactionType;
use Spatie\LaravelData\Data;

class TransactionData extends Data
{
    public function __construct(
        public int $account_id,
        public TransactionType $transactionType,
        public string $reference_number,
        public ?string $debit = null,
        public ?string $credit = null,
        public ?int $member_id = null,
        public ?string $remarks = null,
        public ?string $tag = null,
        public $transaction_date = null
    ) {
        $this->transaction_date ??= today();
    }
}
