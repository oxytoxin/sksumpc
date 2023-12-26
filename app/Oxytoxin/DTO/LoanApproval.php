<?php

namespace App\Oxytoxin\DTO;

use Spatie\LaravelData\Data;

class LoanApproval extends Data
{
    public function __construct(
        public string $name,
        public string $position,
    ) {
    }
}
