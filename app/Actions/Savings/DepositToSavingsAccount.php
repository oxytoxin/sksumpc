<?php

namespace App\Actions\Savings;

use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use Illuminate\Support\Carbon;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\SavingsProvider;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToSavingsAccount
{
    use AsAction;

    public function handle(Member $member, SavingsData $data)
    {
        $savings_account = SavingsAccount::find($data->savings_account_id);

        return Saving::create([
            'savings_account_id' => $data->savings_account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $member->id,
        ]);
    }
}
