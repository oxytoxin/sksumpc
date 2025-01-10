<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoType;
use App\Models\Account;
use App\Models\Member;
use App\Models\PaymentType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\SavingsProvider;

class CashierTransactionsPageMSO
{
    public function handle(MsoType $msoType, $is_deposit, TransactionData $data)
    {
        $member = Member::find($data->member_id);
        $account = Account::find($data->account_id);
        if ($is_deposit) {
            $mso = app(DepositToMsoAccount::class)->handle($msoType, new TransactionData(
                account_id: $data->account_id,
                transactionType: $data->transactionType,
                payment_type_id: $data->payment_type_id,
                reference_number: $data->reference_number,
                credit: $data->credit,
                member_id: $data->member_id,
                payee: $member->full_name,
                transaction_date: $data->transaction_date,
            ));
        } else {
            $reference_number = match ($msoType) {
                MsoType::SAVINGS => SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
                MsoType::IMPREST => ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
                MsoType::LOVE_GIFT => LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE,
            };
            $mso = app(WithdrawFromMsoAccount::class)->handle($msoType, new TransactionData(
                account_id: $data->account_id,
                transactionType: $data->transactionType,
                payment_type_id: $data->payment_type_id,
                reference_number: $reference_number,
                debit: $data->debit,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $data->transaction_date,
            ));

            $mso->revolving_fund()->create([
                'reference_number' => $mso->reference_number,
                'withdrawal' => $data->debit,
                'transaction_date' => $mso->transaction_date,
            ]);
        }
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: Account::getCashOnHand()->id,
            transactionType: $data->transactionType,
            reference_number: $mso->reference_number,
            payment_type_id: $data->payment_type_id,
            debit: $data->credit, //should be credit to balance
            credit: $data->debit, //should be debit to balance
            member_id: $member->id,
            transaction_date: $data->transaction_date,
            payee: $member->full_name,
        ));
        $remarks = match ($msoType) {
            MsoType::SAVINGS => $is_deposit ? 'SAVINGS DEPOSIT' : 'SAVINGS WITHDRAWAL',
            MsoType::IMPREST => $is_deposit ? 'IMPREST DEPOSIT' : 'IMPREST WITHDRAWAL',
            MsoType::LOVE_GIFT => $is_deposit ? 'LOVE GIFT DEPOSIT' : 'LOVE GIFT WITHDRAWAL',
        };

        return [
            'account_number' => $account->number,
            'account_name' => $account->name,
            'reference_number' => $mso->reference_number,
            'amount' => $data->debit ?? $data->credit,
            'payment_type' => PaymentType::find($data->payment_type_id)?->name,
            'payee' => $member->full_name,
            'remarks' => $remarks,
        ];
    }
}
