<?php

namespace App\Oxytoxin;

use App\Models\CapitalSubscription;

class ShareCapitalProvider
{
    public static function fromAmountSubscribed($amount, $terms, $par_value): array
    {
        return [
            'monthly_payment' => $amount / $terms,
            'number_of_shares' => $amount / $par_value,
        ];
    }

    public static function fromNumberOfShares($shares, $terms, $par_value): array
    {
        return [
            'monthly_payment' => floatval($shares) * $par_value / $terms,
            'amount_subscribed' => floatval($shares) * $par_value,
        ];
    }

    public static function generateAmortizationSchedule(CapitalSubscription $cbu): array
    {
        $schedule = [];
        $start = $cbu->transaction_date;
        $term = 1;
        $outstanding_balance = $cbu->outstanding_balance;
        bcscale(10);
        do {
            $schedule[] = [
                'term' => $term,
                'due_date' => $start->addMonthsNoOverflow($term),
                'amount' => $cbu->monthly_payment,
            ];

            $outstanding_balance = round(bcsub($outstanding_balance, $cbu->monthly_payment), 2);
            $term++;
        } while ($term <= $cbu->number_of_terms);

        return $schedule;
    }
}
