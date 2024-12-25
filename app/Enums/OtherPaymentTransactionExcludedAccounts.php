<?php

namespace App\Enums;

enum OtherPaymentTransactionExcludedAccounts: int
{
    case LOAN_RECEIVABLES = 14;
    case SAVINGS_DEPOSIT = 55;
    case TIME_DEPOSIT = 56;
    case CBU_COMMON = 100;
    case CBU_LABORATORY = 101;
    case CBU_PREFERRED = 102;

    public static function get()
    {
        return collect(self::cases())->map(fn ($c) => $c->value)->toArray();
    }
}
