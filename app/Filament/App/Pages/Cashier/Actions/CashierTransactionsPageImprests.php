<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Enums\MsoType;
use App\Models\ImprestAccount;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\ImprestsProvider;

class CashierTransactionsPageImprests
{
    public static function handle($is_deposit, Member $member, ImprestAccount $imprest_account, TransactionType $transaction_type, $payment_type, $reference_number, $amount, $transaction_date)
    {
        if ($is_deposit) {
            $imprest = app(DepositToMsoAccount::class)->handle(MsoType::IMPREST, new TransactionData(
                account_id: $imprest_account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                credit: $amount,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $transaction_date,
            ));
        } else {
            $imprest = app(WithdrawFromMsoAccount::class)->handle(MsoType::IMPREST, new TransactionData(
                account_id: $imprest_account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
                debit: $amount,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $transaction_date,
            ));

            $imprest->revolving_fund()->create([
                'reference_number' => $imprest->reference_number,
                'withdrawal' => $amount,
                'transaction_date' => $imprest->transaction_date,
            ]);
        }

        return [
            'account_number' => $imprest_account->number,
            'account_name' => $imprest_account->name,
            'reference_number' => $imprest->reference_number,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => $is_deposit ? 'IMPREST DEPOSIT' : 'IMPREST WITHDRAWAL',
        ];
    }
}
