<?php

namespace App\Casts;

use App\Data\EmploymentVerificationData;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class EmploymentVerificationCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?EmploymentVerificationData
    {
        if (is_null($value)) {
            return null;
        }

        return EmploymentVerificationData::from($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof EmploymentVerificationData) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return $value;
        }

        return null;
    }
}
