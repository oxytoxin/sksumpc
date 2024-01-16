<?php

namespace App\Oxytoxin\Providers;

use Carbon\CarbonImmutable;

class TimeDepositsProvider
{
    const NUMBER_OF_DAYS = 180;

    const DAYS_ANNUALLY = 365;

    const MINIMUM_DEPOSIT = 10000;

    const FROM_TRANSFER_CODE = '#FROMTIMEDEPOSITS';

    public static function getInterestRate(?float $amount): float
    {
        if ($amount < 99999.99) {
            return 0.03;
        }
        if ($amount >= 10000.00 && $amount < 1000000.00) {
            return 0.035;
        }
        if ($amount >= 1000000.00) {
            return 0.04;
        }

        return 0.03;
    }

    public static function getMaturityAmount(?float $amount): float
    {
        return $amount ? round($amount * (1 + static::getInterestRate($amount) * static::NUMBER_OF_DAYS / static::DAYS_ANNUALLY), 2) : 0;
    }

    public static function getMaturityDate($date_from): CarbonImmutable
    {
        return CarbonImmutable::create($date_from)->addDays(static::NUMBER_OF_DAYS);
    }
}
