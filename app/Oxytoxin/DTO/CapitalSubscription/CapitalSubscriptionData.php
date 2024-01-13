<?php

namespace App\Oxytoxin\DTO\CapitalSubscription;

use Spatie\LaravelData\Data;

class CapitalSubscriptionData extends Data
{
    public function __construct(
        public int $number_of_terms,
        public string $number_of_shares,
        public string $initial_amount_paid,
        public string $monthly_payment,
        public string $amount_subscribed,
        public string $par_value,
        public bool $is_common,
        public string $code
    ) {
    }
}
