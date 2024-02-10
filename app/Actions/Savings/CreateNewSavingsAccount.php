<?php

namespace App\Actions\Savings;

use App\Models\Account;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewSavingsAccount
{
    use AsAction;

    public function handle(SavingsAccountData $savingsAccountData)
    {
        $member_savings = Account::firstWhere('tag', 'member_savings');
        Account::create([
            'name' => $savingsAccountData->name,
            'number' => $savingsAccountData->number,
            'account_type_id' => $member_savings->account_type_id,
            'member_id' => $savingsAccountData->member_id,
            'tag' => 'regular_savings',
        ], $member_savings);
    }
}
