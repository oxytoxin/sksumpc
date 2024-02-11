<?php

namespace App\Oxytoxin\DTO\CashCollectibles;

use Spatie\LaravelData\Data;

class CashCollectiblePaymentData extends Data
{
    public function __construct(
        public int $member_id,
        public int $payment_type_id,
        public string $reference_number,
        public string $amount,
        public ?string $payee = null,
    ) {
    }
}
