<?php

namespace App\Actions\CapitalSubscription;

use App\Models\Account;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewCapitalSubscriptionAccount
{
    use AsAction;

    public function handle(CapitalSubscriptionAccountData $cbuAccountData)
    {
        Account::create([
            'name' => $cbuAccountData->name,
            'number' => $cbuAccountData->number,
            'account_type_id' => $cbuAccountData->parent->account_type_id,
            'member_id' => $cbuAccountData->member_id,
            'tag' => $cbuAccountData->parent->tag,
        ], $cbuAccountData->parent);
    }
}
