<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;

class CashierTransactionsPageOthers
{
    public static function handle($member_id, Account $account, TransactionType $transaction_type, $payment_type, $reference_number, $amount, $transaction_date)
    {
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: Account::getCashOnHand()->id,
            transactionType: $transaction_type,
            reference_number: $reference_number,
            debit: $amount,
            member_id: $member_id,
            remarks: 'Cashier Transaction for Other Accounts',
            transaction_date: $transaction_date,
        ));

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $account->id,
            transactionType: $transaction_type,
            reference_number: $reference_number,
            credit: $amount,
            member_id: $member_id,
            remarks: 'Cashier Transaction for Other Accounts',
            transaction_date: $transaction_date,
            tag: 'other_payments',
        ));
        return [
            'account_number' => $account->number,
            'account_name' => $account->name,
            'reference_number' => $reference_number,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => 'OTHER PAYMENTS',
        ];
    }
}