<?php

namespace App\Enums;

enum FromBillingTypes: int
{
    case MSO_BILLING = 1;
    case LOAN_BILLING = 2;
    case CAPITAL_SUBSCRIPTION_BILLING = 3;
    case CASH_COLLECTIBLE_BILLING = 4;

    public static function get()
    {
        return collect(self::cases())->map(fn($c) => $c->value)->toArray();
    }
    public static function options()
    {
        return collect(self::cases())->mapWithKeys(fn($c) => [$c->value => str($c->name)->replace('_', ' ')->toString()])->toArray();
    }
}
