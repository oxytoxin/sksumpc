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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class WithdrawFromImprestAccount
{


    public function handle(Member $member, ImprestData $data, TransactionType $transactionType, $isJevOrDv = false)
    {
        if ($member->imprests()->sum('amount') - $data->amount < 500) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }
        DB::beginTransaction();
        $imprest_account = $member->imprest_account;
        $imprest = Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount * -1,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        if (!$isJevOrDv) {
            if ($data->payment_type_id == 1) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashOnHand()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $imprest->reference_number,
                    credit: $data->amount,
                    member_id: $imprest->member_id,
                    remarks: 'Member Withdrawal from Imprest',
                    transaction_date: $data->transaction_date,
                ));
            }
            if ($data->payment_type_id == 4) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashInBankMSO()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $imprest->reference_number,
                    credit: $data->amount,
                    member_id: $imprest->member_id,
                    remarks: 'Member Withdrawal from Imprest',
                    transaction_date: $data->transaction_date,
                ));
            }
        }

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $imprest_account->id,
            transactionType: $transactionType,
            payment_type_id: $data->payment_type_id,
            reference_number: $imprest->reference_number,
            debit: $data->amount,
            member_id: $member->id,
            remarks: 'Member Withdrawal from Imprest',
            transaction_date: $data->transaction_date,
            tag: 'member_imprest_withdrawal',
        ));
        DB::commit();

        return $imprest;
    }
}
