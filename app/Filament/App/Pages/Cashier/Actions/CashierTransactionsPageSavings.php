<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Enums\MsoType;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;

class CashierTransactionsPageSavings
{
    public static function handle($is_deposit, Member $member, SavingsAccount $savings_account, TransactionType $transaction_type, $payment_type, $reference_number, $amount, $transaction_date)
    {
        if ($is_deposit) {
            $savings = app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, new TransactionData(
                account_id: $savings_account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                credit: $amount,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $transaction_date,
            ));
        } else {
            $savings = app(WithdrawFromMsoAccount::class)->handle(MsoType::SAVINGS, new TransactionData(
                account_id: $savings_account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
                debit: $amount,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $transaction_date,
            ));

            $savings->revolving_fund()->create([
                'reference_number' => $savings->reference_number,
                'withdrawal' => $amount,
                'transaction_date' => $savings->transaction_date,
            ]);
        }

        return [
            'account_number' => $savings_account->number,
            'account_name' => $savings_account->name,
            'reference_number' => $savings->reference_number,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => $is_deposit ? 'SAVINGS DEPOSIT' : 'SAVINGS WITHDRAWAL',
        ];
    }
}
