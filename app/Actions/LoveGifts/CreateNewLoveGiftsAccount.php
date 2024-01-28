<?php

namespace App\Actions\LoveGifts;

use App\Models\LoveGiftAccount;
use App\Oxytoxin\DTO\MSO\Accounts\LoveGiftAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewLoveGiftsAccount
{
    use AsAction;

    public function handle(LoveGiftAccountData $loveGiftData)
    {
        LoveGiftAccount::create($loveGiftData->toArray());
    }
}
