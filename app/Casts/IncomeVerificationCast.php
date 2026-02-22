<?php

    namespace App\Casts;

    use App\Data\IncomeVerificationData;
    use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
    use Illuminate\Database\Eloquent\Model;

    class IncomeVerificationCast implements CastsAttributes
    {
        public function get(Model $model, string $key, mixed $value, array $attributes): ?IncomeVerificationData
        {
            if (is_null($value)) {
                return null;
            }

            return IncomeVerificationData::from($value);
        }

        public function set(Model $model, string $key, mixed $value, array $attributes): ?string
        {
            if (is_null($value)) {
                return null;
            }

            if ($value instanceof IncomeVerificationData) {
                return json_encode($value->toArray());
            }

            if (is_array($value)) {
                return json_encode($value);
            }

            return null;
        }
    }
