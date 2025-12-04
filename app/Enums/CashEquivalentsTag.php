<?php

namespace App\Enums;

enum CashEquivalentsTag: string
{
    case CASH_ON_HAND = 'cash_on_hand';
    case CASH_IN_BANK = 'cash_in_bank';
    case DBP_GENERAL_FUND = 'cash_in_bank_dbp_gf';
    case DBP_MSO_FUND = 'cash_in_bank_dbp_mso';
    case LBP = 'cash_in_bank_lbp';
    case PETTY_CASH_FUND = 'petty_cash_fund';
    case REVOLVING_FUND = 'revolving_fund';

    public static function get()
    {
        return collect(self::cases())->map(fn ($c) => $c->value)->toArray();
    }
}
