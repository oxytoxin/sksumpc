<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class EmploymentVerificationData extends Data
{
    public function __construct(
        public ?string $employer,
        public ?string $office_address,
        public ?string $business_form,
        public ?string $nature_of_business,
        public ?int $year_connected,
        public ?string $position,
        public ?string $employment_status,
    ) {}
}
