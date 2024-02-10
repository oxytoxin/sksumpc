<?php

namespace App\Actions\LoveGifts;

use App\Models\Account;
use App\Oxytoxin\DTO\MSO\Accounts\LoveGiftAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewLoveGiftsAccount
{
    use AsAction;

    public function handle(LoveGiftAccountData $loveGiftAccountData)
    {
        $member_savings = Account::firstWhere('tag', 'member_savings');
        Account::create([
            'name' => $loveGiftAccountData->name,
            'number' => $loveGiftAccountData->number,
            'account_type_id' => $member_savings->account_type_id,
            'member_id' => $loveGiftAccountData->member_id,
            'tag' => 'love_gift_savings',
        ], $member_savings);
    }
}
