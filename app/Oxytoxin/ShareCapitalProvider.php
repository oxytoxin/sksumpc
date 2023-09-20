<?php

namespace App\Oxytoxin;

class ShareCapitalProvider
{
    const INITIAL_SHARES = 20;
    const INITIAL_AMOUNT = 25000;
    const INITIAL_PAID = 6500;
    const INITIAL_NUMBER_OF_TERMS = 12;
    const INITIAL_CAPITAL_CODE = 'Initial Capital Subscription';

    public static function getSubscriptionAmount(int|float $amount): int|float
    {
        return round((static::INITIAL_AMOUNT - $amount) / 12, 2);
    }
}
