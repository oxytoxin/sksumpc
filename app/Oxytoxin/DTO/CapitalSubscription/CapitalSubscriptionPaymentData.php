<?php

namespace App\Oxytoxin\DTO\CapitalSubscription;

use Spatie\LaravelData\Data;

class CapitalSubscriptionPaymentData extends Data
{
    public function __construct(
        public int $payment_type_id,
        public string $reference_number,
        public string $amount,
        public $transaction_date = null
    ) {
        $this->transaction_date ??= today();
    }
}
