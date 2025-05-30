<?php

namespace App\Oxytoxin\DTO\Loan;

use Spatie\LaravelData\Data;

class LoanPaymentData extends Data
{
    public function __construct(
        public int $payment_type_id,
        public string $reference_number,
        public string $amount,
        public bool $buy_out = false,
        public ?string $remarks = null,
        public $transaction_date = null,
        public $from_billing_type = null,
    ) {
        $this->transaction_date = $transaction_date ?? today();
    }
}
