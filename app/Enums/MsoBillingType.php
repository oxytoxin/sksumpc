<?php

    namespace App\Enums;

    use Filament\Support\Contracts\HasLabel;

    enum MsoBillingType: int implements HasLabel
    {
        case SAVINGS = 1;
        case IMPREST = 2;
        case LOVE_GIFT = 3;

        public function getLabel(): string
        {
            return match ($this) {
                self::SAVINGS => 'Savings',
                self::IMPREST => 'Imprest',
                self::LOVE_GIFT => 'Love Gift',
            };
        }
    }
