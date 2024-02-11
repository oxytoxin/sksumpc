<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;

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

function sum_no_children_recursive(Collection $items, string $key): float
{
    $sum = 0;
    foreach ($items as $item) {
        if ($item->children_count == 0) {
            $sum += $item[$key];
        } else {
            $sum += sum_no_children_recursive($item->children ?? collect(), $key);
        }
    }

    return $sum;
}

function oxy_get_month_range(): array
{
    return collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create(null, $m)->format('F')])->toArray();
}

function oxy_get_year_range(): array
{
    return collect(range(today()->addYears(10)->year, 2000))->mapWithKeys(fn ($y) => [$y => $y])->toArray();
}

function renumber_format($number, $decimals = 0)
{
    if (! $number || ! floatval($number)) {
        return '';
    }
    if ($number < 0) {
        $result = str('(')->append(number_format(abs($number), 2))->append(')');
    } else {
        $result = number_format($number, $decimals);
    }

    return $result;
}
