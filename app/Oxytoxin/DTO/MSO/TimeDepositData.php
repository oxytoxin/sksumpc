<?php

namespace App\Oxytoxin\DTO\MSO;

use Spatie\LaravelData\Data;

class TimeDepositData extends Data
{
    public function __construct(
        public int $member_id,
        public $maturity_date,
        public int $payment_type_id,
        public string $reference_number,
        public string $amount,
        public string $maturity_amount,
        public $withdrawal_date = null,
        public $transaction_date = null,
    ) {
        $this->transaction_date ??= today();
    }
}
