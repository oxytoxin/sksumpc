<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CashierTransactionsPageOthers
{
    public static function handle($member_id, Account $account, TransactionType $transaction_type, $payment_type, $reference_number, $amount, $transaction_date, $payee)
    {
        if (in_array($account->tag, ['member_common_cbu_paid', 'member_preferred_cbu_paid', 'member_laboratory_cbu_paid'])) {
            $member = Member::find($member_id);
            app(PayCapitalSubscription::class)
                ->handle(
                    cbu: $member->capital_subscriptions_common,
                    transactionData: new TransactionData(
                        account_id: $member->capital_subscription_account->id,
                        transactionType: $transaction_type,
                        reference_number: $reference_number,
                        payment_type_id: $payment_type->id,
                        credit: $amount,
                        member_id: $member->id,
                        transaction_date: $transaction_date,
                        payee: $member->full_name,
                    )
                );

        } else {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashOnHand()->id,
                transactionType: $transaction_type,
                reference_number: $reference_number,
                payment_type_id: $payment_type->id,
                payee: $payee,
                debit: $amount,
                member_id: $member_id,
                remarks: 'Cashier Transaction for Other Accounts',
                transaction_date: $transaction_date,
            ));
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                payee: $payee,
                credit: $amount,
                member_id: $member_id,
                remarks: 'Cashier Transaction for Other Accounts',
                transaction_date: $transaction_date,
                tag: 'other_payments',
            ));
        }

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
