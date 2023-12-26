<?php

namespace App\Oxytoxin\DTO;

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
