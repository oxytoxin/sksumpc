<?php

namespace App\Oxytoxin;

class SavingsProvider
{
    const INTEREST_RATE = 0.01;
    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;

    public static function calculateInterest($amount, $interest, $days)
    {
        if ($amount < static::MINIMUM_AMOUNT_FOR_INTEREST) {
            return 0;
        }

        return $amount * $interest * $days / 365;
    }
}
