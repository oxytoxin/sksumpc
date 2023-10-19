<?php

namespace App\Oxytoxin;

class ShareCapitalProvider
{
    const REGULAR_INITIAL_SHARES = 50;
    const REGULAR_INITIAL_AMOUNT = 25000;
    const REGULAR_INITIAL_PAID = 6500;
    const ASSOCIATE_INITIAL_SHARES = 20;
    const ASSOCIATE_INITIAL_AMOUNT = 10000;
    const ASSOCIATE_INITIAL_PAID = 2500;
    const PAR_VALUE = 500;
    const INITIAL_NUMBER_OF_TERMS = 12;
    const ADDITIONAL_NUMBER_OF_TERMS = 36;
    const INITIAL_CAPITAL_CODE = 'Initial Capital Subscription';
    const EXISTING_CAPITAL_CODE = 'Existing Capital Subscription';

    public static function getSubscriptionAmount(int|float $amount): int|float
    {
        return round($amount * static::INITIAL_NUMBER_OF_TERMS, 2);
    }

    public static function fromAmountSubscribed($amount, $terms): array
    {
        return [
            'monthly_payment' => $amount / $terms,
            'number_of_shares' => $amount / static::PAR_VALUE,
        ];
    }

    public static function fromMonthlyPayment($amount, $terms): array
    {
        return [
            'number_of_shares' => $amount * $terms /  static::PAR_VALUE,
            'amount_subscribed' => $amount * $terms,
        ];
    }

    public static function fromNumberOfShares($shares, $terms): array
    {
        return [
            'monthly_payment' => floatval($shares) * static::PAR_VALUE / $terms,
            'amount_subscribed' => floatval($shares) * static::PAR_VALUE,
        ];
    }
}
