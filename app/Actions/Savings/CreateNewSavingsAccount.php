<?php

namespace App\Actions\Savings;

use App\Models\Member;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewSavingsAccount
{
    use AsAction;

    public function handle(SavingsAccountData $savingsAccountData)
    {
        SavingsAccount::create($savingsAccountData->toArray());
    }
}
