<?php

namespace App\Actions\Savings;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Saving;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use DB;

class DepositToSavingsAccount
{
    public function handle(TransactionData $data)
    {
        $data->remarks = 'Member Savings Deposit';
        $data->tag = 'member_savings_deposit';
        DB::beginTransaction();
        $savings = Saving::create([
            'savings_account_id' => $data->account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->credit,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);
        app(CreateTransaction::class)->handle($data);
        DB::commit();

        return $savings;
    }
}
