<?php

namespace App\Oxytoxin\DTO\Loan;

use Spatie\LaravelData\Data;

class LoanData extends Data
{

    public function __construct(
        public int $member_id,
        public int $loan_application_id,
        public int $loan_type_id,
        public string $reference_number,
        public string $priority_number,
        public string $gross_amount,
        public int $number_of_terms,
        public string $interest_rate,
        public string $interest,
        public string $monthly_payment,
        public $release_date,
        public $transaction_date = null,
        public array $deductions = [],
        public ?string $check_number = null,
    ) {
        $this->transaction_date ??= today();
    }
}
