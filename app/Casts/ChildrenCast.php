<?php

    namespace App\Casts;

    use App\Data\ChildData;
    use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
    use Illuminate\Database\Eloquent\Model;

    class ChildrenCast implements CastsAttributes
    {
        public function get(Model $model, string $key, mixed $value, array $attributes): array
        {
            if (is_null($value)) {
                return [];
            }

            $decoded = is_string($value) ? json_decode($value, true) : $value;

            return collect($decoded)
                ->map(fn($item) => is_array($item) ? ChildData::from($item) : $item)
                ->all();
        }

        public function set(Model $model, string $key, mixed $value, array $attributes): ?string
        {
            if (is_null($value)) {
                return null;
            }

            return json_encode(collect($value)
                ->map(fn($item) => $item instanceof ChildData ? $item->toArray() : $item)
                ->toArray());
        }
    }
