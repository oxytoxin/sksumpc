<?php

namespace App\Oxytoxin\DTO\MSO;

use Spatie\LaravelData\Data;

class SavingsData extends Data
{
    public function __construct(
        public int $payment_type_id,
        public string $reference_number,
        public float $amount,
        public int $savings_account_id
    ) {
    }
}
