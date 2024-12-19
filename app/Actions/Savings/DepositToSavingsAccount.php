<?php

namespace App\Actions\Savings;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use DB;


class DepositToSavingsAccount
{


    public function handle(Member $member, SavingsData $data, TransactionType $transactionType, $isJevOrDv = false)
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
        if (!$isJevOrDv) {
            if ($data->payment_type_id == 1) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashOnHand()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $savings->reference_number,
                    debit: $savings->amount,
                    member_id: $savings->member_id,
                    remarks: 'Member Deposit to Savings',
                    transaction_date: $data->transaction_date,
                ));
            }
            if ($data->payment_type_id == 4) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashInBankMSO()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $savings->reference_number,
                    debit: $savings->amount,
                    member_id: $savings->member_id,
                    remarks: 'Member Deposit to Savings',
                    transaction_date: $data->transaction_date,
                ));
            }
        }

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $savings_account->id,
            transactionType: $transactionType,
            payment_type_id: $data->payment_type_id,
            reference_number: $savings->reference_number,
            credit: $savings->amount,
            member_id: $member->id,
            remarks: 'Member Deposit to Savings',
            tag: 'member_savings_deposit',
            transaction_date: $data->transaction_date,
        ));
        DB::commit();

        return $savings;
    }
}
