<?php

namespace App\Oxytoxin\DTO\Loan;

use App\Models\Loan;
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
        public array $disclosure_sheet_items = [],
        public ?string $check_number = null,
        public ?string $account_number = null,
    ) {
        if (! $this->account_number) {
            $this->account_number = str(str_pad($loan_type_id, 4, '0', STR_PAD_LEFT))
                ->append('-')
                ->append(str_pad((Loan::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
        }
        $this->transaction_date ??= today();
    }
}
