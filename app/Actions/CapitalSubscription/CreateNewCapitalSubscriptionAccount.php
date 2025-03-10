<?php

namespace App\Actions\CapitalSubscription;

use App\Models\Account;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;

class CreateNewCapitalSubscriptionAccount
{
    public function handle(CapitalSubscriptionAccountData $cbuAccountData)
    {
        Account::firstOrCreate([
            'member_id' => $cbuAccountData->member_id,
            'tag' => $cbuAccountData->parent->tag,
        ], [
            'name' => $cbuAccountData->name,
            'number' => $cbuAccountData->number,
            'account_type_id' => $cbuAccountData->parent->account_type_id,
            'parent_id' => $cbuAccountData->parent->id,
        ]);
    }
}
