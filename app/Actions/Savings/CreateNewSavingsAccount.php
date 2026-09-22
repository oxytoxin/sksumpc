<?php

namespace App\Actions\Savings;

use App\Models\Account;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use Illuminate\Validation\ValidationException;

class CreateNewSavingsAccount
{
    public function handle(SavingsAccountData $savingsAccountData): SavingsAccount
    {
        if (Member::find($savingsAccountData->member_id)?->terminated_at) {
            throw ValidationException::withMessages([
                'member_id' => 'A new savings account cannot be created for a closed account.',
            ]);
        }

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
