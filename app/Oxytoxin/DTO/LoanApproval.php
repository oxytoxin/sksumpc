<?php

namespace App\Oxytoxin\DTO;

use App\Models\User;
use Spatie\LaravelData\Data;

class LoanApproval extends Data
{
    public function __construct(
        public int $approver_id,
        public string $position,
        public ?bool $approved = null,
        public ?string $remarks = null
    ) {
    }
}
