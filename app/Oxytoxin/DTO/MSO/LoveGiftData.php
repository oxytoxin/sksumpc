<?php

namespace App\Oxytoxin\DTO\MSO;

use Spatie\LaravelData\Data;

class LoveGiftData extends Data
{
    public function __construct(
        public int $payment_type_id,
        public string $reference_number,
        public float $amount,
        public $transaction_date = null
    ) {
        $this->transaction_date ??= today();
    }
}
