<?php

namespace App\Actions\Savings;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToSavingsAccount
{
    use AsAction;

    public function handle(Member $member, SavingsData $data, TransactionType $transactionType)
    {
        DB::beginTransaction();
        $savings_account = SavingsAccount::find($data->savings_account_id);
        $savings = Saving::create([
            'savings_account_id' => $data->savings_account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $savings_account->id,
            transactionType: $transactionType,
            reference_number: $savings->reference_number,
            credit: $savings->amount,
            member_id: $member->id,
            remarks: 'Member Deposit to Savings'
        ));
        DB::commit();

        return $savings;
    }
}
