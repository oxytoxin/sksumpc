<?php

namespace App\Oxytoxin\Services;

class InterestCalculator
{
    public function calculate($amount, $rate, $days, $minimum_amount = 0, $total_days = 365)
    {
        if ($amount < $minimum_amount) {
            return 0;
        }

        return $amount * $rate * $days / $total_days;
    }
}
