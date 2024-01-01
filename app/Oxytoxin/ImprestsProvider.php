<?php

namespace App\Oxytoxin;

use App\Models\Imprest;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\ImprestData;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ImprestsProvider
{
    const INTEREST_RATE = 0.02;
    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;
    const FROM_TRANSFER_CODE = '#TRANSFERFROMIMPRESTS';
}
