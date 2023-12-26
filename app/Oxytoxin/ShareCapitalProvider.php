<?php

namespace App\Oxytoxin;

use App\Models\CapitalSubscription;

class ShareCapitalProvider
{
    const PAR_VALUE = 500;

    const INITIAL_NUMBER_OF_TERMS = 12;

    const ADDITIONAL_NUMBER_OF_TERMS = 36;

    const INITIAL_CAPITAL_CODE = 'Initial Capital Subscription';

    const EXISTING_CAPITAL_CODE = 'Existing Capital Subscription';

    public static function fromAmountSubscribed($amount, $terms): array
    {
        return [
            'monthly_payment' => $amount / $terms,
            'number_of_shares' => $amount / static::PAR_VALUE,
        ];
    }

    public static function fromNumberOfShares($shares, $terms): array
    {
        return [
            'monthly_payment' => floatval($shares) * static::PAR_VALUE / $terms,
            'amount_subscribed' => floatval($shares) * static::PAR_VALUE,
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
