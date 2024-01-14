<?php

namespace App\Oxytoxin\DTO\Loan;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class LoanPaymentData extends Data
{

    public function __construct(
        public int $payment_type_id,
        public string $reference_number,
        public string $amount,
        public ?string $remarks = null,
        public Carbon|CarbonImmutable|null $transaction_date = null,
    ) {
        $this->transaction_date = $transaction_date ?? today();
    }
}
