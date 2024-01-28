<?php

use Carbon\Carbon;

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
    if (!$number || !floatval($number))
        return '';
    return number_format($number, $decimals);
}
