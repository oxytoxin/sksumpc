<?php

namespace App\Enums;
enum MsoTransactionTag: string
{
    case MEMBER_SAVINGS_DEPOSIT = "member_savings_deposit";
    case MEMBER_SAVINGS_WITHDRAWAL = "member_savings_withdrawal";
    case MEMBER_IMPREST_DEPOSIT = "member_imprest_deposit";
    case MEMBER_IMPREST_WITHDRAWAL = "member_imprest_withdrawal";
    case MEMBER_LOVE_GIFT_DEPOSIT = "member_love_gift_deposit";
    case MEMBER_LOVE_GIFT_WITHDRAWAL = "member_love_gift_withdrawal";
    case MEMBER_TIME_DEPOSIT = "member_time_deposit";

    public static function get()
    {
        return collect(self::cases())->map(fn($c) => $c->value)->toArray();
    }
}