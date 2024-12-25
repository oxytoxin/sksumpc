<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\Providers\SavingsProvider;

class CashierTransactionsPageSavings
{
    public static function handle($is_deposit, Member $member, SavingsAccount $savings_account, TransactionType $transaction_type, $payment_type, $reference_number, $amount, $transaction_date)
    {
        if ($is_deposit) {
            app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                amount: $amount,
                savings_account_id: $savings_account->id,
                transaction_date: $transaction_date,
            ), $transaction_type);

        } else {
            $savings = app(WithdrawFromSavingsAccount::class)->handle($member, new SavingsData(
                payment_type_id: $payment_type->id,
                reference_number: SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
                amount: $amount,
                savings_account_id: $savings_account->id,
                transaction_date: $transaction_date,
            ), $transaction_type);
            $savings->revolving_fund()->create([
                'reference_number' => $savings->reference_number,
                'withdrawal' => $amount,
                'transaction_date' => $savings->transaction_date,
            ]);
        }

        return [
            'account_number' => $savings_account->number,
            'account_name' => $savings_account->name,
            'reference_number' => $is_deposit ? $reference_number : SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => $is_deposit ? 'SAVINGS DEPOSIT' : 'SAVINGS WITHDRAWAL',
        ];
    }
}
