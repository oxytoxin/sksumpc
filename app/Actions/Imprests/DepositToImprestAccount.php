<?php

namespace App\Actions\Imprests;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Imprest;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\ImprestsProvider;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToImprestAccount
{
    use AsAction;

    public function handle(Member $member, ImprestData $data, TransactionType $transactionType, $transact = true)
    {
        DB::beginTransaction();
        $imprest_account = $member->imprest_account;
        $imprest = Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        if ($transact) {
            if ($data->payment_type_id == 1) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashOnHand()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $imprest->reference_number,
                    debit: $imprest->amount,
                    member_id: $imprest->member_id,
                    remarks: 'Member Deposit to Imprest',
                    transaction_date: $data->transaction_date,
                ));
            }
            if ($data->payment_type_id == 4) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashInBankMSO()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $imprest->reference_number,
                    debit: $imprest->amount,
                    member_id: $imprest->member_id,
                    remarks: 'Member Deposit to Imprest',
                    transaction_date: $data->transaction_date,
                ));
            }
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $imprest_account->id,
                transactionType: $transactionType,
                payment_type_id: $data->payment_type_id,
                reference_number: $imprest->reference_number,
                credit: $imprest->amount,
                member_id: $member->id,
                remarks: 'Member Deposit to Imprest',
                tag: 'member_imprest_deposit',
                transaction_date: $data->transaction_date,
            ));
        }
        DB::commit();

        return $imprest;
    }
}
