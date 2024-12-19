<?php

namespace App\Actions\Imprests;

use App\Models\Account;
use App\Models\ImprestAccount;
use App\Oxytoxin\DTO\MSO\Accounts\ImprestAccountData;


class CreateNewImprestsAccount
{


    public function handle(ImprestAccountData $imprestAccountData)
    {
        $member_savings = Account::firstWhere('tag', 'member_savings');
        return ImprestAccount::create([
            'name' => $imprestAccountData->name,
            'number' => $imprestAccountData->number,
            'account_type_id' => $member_savings->account_type_id,
            'member_id' => $imprestAccountData->member_id,
            'tag' => 'imprest_savings',
        ], $member_savings);
    }
}
