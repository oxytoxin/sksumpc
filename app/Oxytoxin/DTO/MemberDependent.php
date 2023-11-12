<?php

namespace App\Oxytoxin\DTO;

use Carbon\Carbon;
use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
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
