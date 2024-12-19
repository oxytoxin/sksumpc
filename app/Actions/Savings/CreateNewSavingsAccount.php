<?php

namespace App\Actions\Savings;

use App\Models\Account;
use App\Models\SavingsAccount;

use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;

class CreateNewSavingsAccount
{


    public function handle(SavingsAccountData $savingsAccountData): SavingsAccount
    {
        $member_savings = Account::firstWhere('tag', 'member_savings');
        return SavingsAccount::create([
            'name' => $savingsAccountData->name,
            'number' => $savingsAccountData->number,
            'account_type_id' => $member_savings->account_type_id,
            'member_id' => $savingsAccountData->member_id,
            'tag' => 'regular_savings',
        ], $member_savings);
    }
}
