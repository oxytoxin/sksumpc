<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

function format_percentage($new, $old): string
{
    if ($old != 0) {
        $percentage = ($new - $old) / $old * 100;
        if (round($percentage, 4) == 0) {
            return '-';
        }

        return str(round($percentage, 4))->append('%');
    } else {
        return '-';
    }
}

function format_account_name_from_depth(string $name, int $depth): string
{
    return str($name)->explode(':')[$depth] ?? $name;
}

function sum_recursive(Collection $items, string $key): float
{
    $sum = $items->sum($key);

    foreach ($items as $item) {
        $sum += sum_recursive($item->children ?? collect(), $key);
    }

    return $sum;
}

function sum_no_children_recursive(Collection|Model $items, string $key): float
{
    $sum = 0;
    if ($items instanceof Model) {
        if ($items->children_count == 0) {
            $sum += $items[$key];
        } else {
            $sum += sum_no_children_recursive($items->children ?? collect(), $key);
        }
    } else {
        foreach ($items as $item) {
            if ($item->children_count == 0) {
                $sum += $item[$key];
            } else {
                $sum += sum_no_children_recursive($item->children ?? collect(), $key);
            }
        }
    }

    return $sum;
}

function filterRecursive($collection, $callback)
{
    return $collection->flatMap(function ($item) use ($callback) {
        // Initialize an empty collection for matching items
        $results = collect([]);

        // Check if the item itself should be included
        if ($callback($item)) {
            $results->push($item);
        }

        // Recursively filter children and merge results
        if ($item->children->count() > 0) {
            $children = filterRecursive($item->children, $callback);
            $results = $results->merge($children);
        }

        return $results;
    });
}

function flattenRecursively(Collection $collection): Collection
{
    return $collection->flatMap(function ($item) {
        info($item->children->count());
        if ($item->children) {
            return flattenRecursively(collect($item->children));
        }

        return [$item];
    });
}

function findRecursive($collection, $callback)
{
    return $collection->flatMap(function ($item) use ($callback) {
        // Initialize an empty collection for matching items
        $results = collect([]);

        // Check if the item itself should be included
        if ($callback($item)) {
            $results->push($item);
        }

        // Recursively filter children and merge results
        if ($item->children->count() > 0) {
            $children = filterRecursive($item->children, $callback);
            $results = $results->merge($children);
        }

        return $results;
    })[0] ?? null;
}

function oxy_get_month_range(): array
{
    return collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create(null, $m)->format('F')])->toArray();
}

function oxy_get_year_range(): array
{
    return collect(range(today()->addYears(10)->year, 2000))->mapWithKeys(fn ($y) => [$y => $y])->toArray();
}

function renumber_format($number, $decimals = 2)
{
    if (! $number || ! floatval($number)) {
        return '';
    }
    if ($number < 0) {
        $result = str('(')->append(number_format(abs($number), $decimals))->append(')');
    } else {
        $result = number_format($number, $decimals);
    }

    return $result;
}
