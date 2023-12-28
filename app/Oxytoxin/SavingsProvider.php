<?php

namespace App\Oxytoxin;

use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\SavingsData;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class SavingsProvider
{
    const INTEREST_RATE = 0.01;
    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;
    const FROM_TRANSFER_CODE = '#TRANSFERFROMSAVINGS';
}
