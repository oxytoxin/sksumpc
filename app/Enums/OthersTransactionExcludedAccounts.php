<?php

    namespace App\Enums;

    enum OthersTransactionExcludedAccounts: int
    {
        case CASH_AND_CASH_EQUIVALENTS = 1;
        case LOAN_RECEIVABLES = 14;
        case SAVINGS_DEPOSIT = 55;
        case TIME_DEPOSITS = 56;
        case INTEREST_INCOME_FROM_LOANS = 75;
        case RICE = 151;
        case GROCERIES = 154;
        case RESERVATION_FEES_DORM = 80;
        case DORMITORY = 94;
        case RESERVATION = 157;
        case OTHER_INCOME_ELECTRICITY = 162;
        case OTHER_INCOME_RENTALS = 160;
        case MEMBERSHIP_FEES = 81;
        case LABORATORY_CBU_PAID = 101;
        case LABORATORY_CBU_DEPOSIT = 105;
        case TIME_DEPOSIT_INTEREST_EXPENSE = 117;

        public static function ids()
        {
            return collect(self::cases())->map(fn($c) => $c->value)->toArray();
        }
    }
