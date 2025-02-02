<?php

namespace App\Oxytoxin\Providers;

class SavingsProvider
{
    const INTEREST_RATE = 0.01;

    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;

    const FROM_TRANSFER_CODE = '#TRANSFERFROMSAVINGS';

    const WITHDRAWAL_TRANSFER_CODE = '#WITHDRAWFROMSAVINGS';

    const DEPOSIT_TRANSFER_CODE = '#DEPOSITFROMSAVINGS';
}
