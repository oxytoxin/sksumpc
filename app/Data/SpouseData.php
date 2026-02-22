<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SpouseData extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $nickname,
        public ?string $middle_name,
        public ?string $date_of_birth,
        public ?int $age,
        public ?string $contact_number,
        public ?string $civil_status,
        public ?string $nationality,
        public ?string $address,
        public ?string $highest_educational_attainment,
        public ?string $school,
    ) {}
}
