<?php

namespace App\Enums;

enum OthersTransactionExcludedAccounts: int
{
    case LOAN_RECEIVABLES = 14;
    case INTEREST_INCOME_FROM_LOANS = 75;
    case RICE = 151;
    case RESERVATION_FEES_DORM = 80;
    case DORMITORY = 94;
    case RESERVATION = 157;
    case MEMBERSHIP_FEES = 81;
    case LABORATORY_CBU_PAID = 101;
    case LABORATORY_CBU_DEPOSIT = 105;

    public static function get()
    {
        return collect(self::cases())->map(fn($c) => $c->value)->toArray();
    }
}
