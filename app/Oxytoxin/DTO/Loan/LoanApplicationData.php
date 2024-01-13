<?php

namespace App\Oxytoxin\DTO\Loan;

use Spatie\LaravelData\Data;

class LoanApplicationData extends Data
{
    public function __construct(
        public int $member_id,
        public int $loan_type_id,
        public int $number_of_terms,
        public ?string $priority_number,
        public string $desired_amount,
        public string $monthly_payment,
        public $transaction_date = null,
        public ?string $purpose = null
    ) {
        $this->transaction_date ??= today();
    }
}
