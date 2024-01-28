<?php

namespace App\Oxytoxin\DTO\Membership;

use Spatie\LaravelData\Data;

class MemberDependent extends Data
{
    public function __construct(
        public string $name,
        public string $dob,
        public string $relationship,
    ) {
    }
}
