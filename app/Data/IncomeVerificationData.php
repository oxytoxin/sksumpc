<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class IncomeVerificationData extends Data
{
    public function __construct(
        public ?float $basic_salary,
        public ?float $allowances,
        public ?float $business_income,
        public ?float $other_income,
        public ?float $monthly_income,
        public ?float $annual_income,
    ) {}
}
