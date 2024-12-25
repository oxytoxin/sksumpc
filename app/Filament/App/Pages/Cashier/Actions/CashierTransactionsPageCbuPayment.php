<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CashierTransactionsPageCbuPayment
{
    public static function handle(Member $member, TransactionType $transaction_type, $reference_number, PaymentType $payment_type, $amount, $transaction_date): array
    {
        app(PayCapitalSubscription::class)->handle($member->capital_subscriptions_common, new TransactionData(
            account_id: $member->capital_subscription_account->id,
            transactionType: $transaction_type,
            reference_number: $reference_number,
            payment_type_id: $payment_type->id,
            credit: $amount,
            member_id: $member->id,
            transaction_date: $transaction_date,
            payee: $member->full_name,
        ));

        return [
            'account_number' => $member->capital_subscription_account->number,
            'account_name' => $member->capital_subscription_account->name,
            'reference_number' => $reference_number,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => 'CBU PAYMENT',
        ];
    }
}
