<?php

namespace App\Oxytoxin\DTO\Transactions;

use App\Models\Member;
use App\Models\TransactionType;
use Spatie\LaravelData\Data;

class TransactionData extends Data
{
    public function __construct(
        public int $account_id,
        public TransactionType $transactionType,
        public string $reference_number,
        public int $payment_type_id,
        public ?string $debit = null,
        public ?string $credit = null,
        public ?int $member_id = null,
        public ?string $remarks = null,
        public ?string $tag = null,
        public $transaction_date = null,
        public $payee = null,
    ) {
        $this->transaction_date ??= (config('app.transaction_date') ?? today());
        if (! $this->payee) {
            $this->payee = $this->member_id ? Member::find($this->member_id)->full_name : 'SKSU-MPC';
        }
    }
}
