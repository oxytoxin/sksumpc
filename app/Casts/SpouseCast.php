<?php

    namespace App\Casts;

    use App\Data\SpouseData;
    use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
    use Illuminate\Database\Eloquent\Model;

    class SpouseCast implements CastsAttributes
    {
        public function get(Model $model, string $key, mixed $value, array $attributes): ?SpouseData
        {
            if (is_null($value)) {
                return null;
            }

            return SpouseData::from($value);
        }

        public function set(Model $model, string $key, mixed $value, array $attributes): ?string
        {
            if (is_null($value)) {
                return null;
            }

            if ($value instanceof SpouseData) {
                return json_encode($value->toArray());
            }

            if (is_array($value)) {
                return json_encode($value);
            }

            return null;
        }
    }
