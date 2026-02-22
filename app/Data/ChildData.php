<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ChildData extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $birthdate,
        public ?string $course_and_school,
    ) {}
}
