<?php

namespace App\Oxytoxin\Providers;

use Carbon\CarbonImmutable;

class TimeDepositsProvider
{
    const NUMBER_OF_DAYS = 180;

    const DAYS_ANNUALLY = 360;

    const MINIMUM_DEPOSIT = 10000;

    const FROM_TRANSFER_CODE = '#FROMTIMEDEPOSITS';

    const TERMINATION_INTEREST_RATE = 0.01;

    public static function getInterestRate(?float $amount): float
    {
        if ($amount < 100000) {
            return 0.03;
        }
        if ($amount >= 100000.00 && $amount < 1000000.00) {
            return 0.035;
        }
        if ($amount >= 1000000.00) {
            return 0.04;
        }

        return 0.03;
    }

    public static function getMaturityAmount(?float $amount, $interest_rate = null, $number_of_days = null): float
    {
        $interest_rate ??= static::getInterestRate($amount);
        $number_of_days ??= static::NUMBER_OF_DAYS;

        return $amount ? round($amount * (1 + $interest_rate * floatval($number_of_days ?? 0) / static::DAYS_ANNUALLY), 2) : 0;
    }

    public static function getMaturityDate($date_from, $number_of_days = null): CarbonImmutable
    {
        $number_of_days ??= static::NUMBER_OF_DAYS;

        return CarbonImmutable::create($date_from)->addDays(floatval($number_of_days ?? 0));
    }
}
