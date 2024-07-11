<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;

class CashierTransactionsPageCbuPayment
{

    public static function handle(Member $member, TransactionType $transaction_type, $reference_number, PaymentType $payment_type, $amount, $transaction_date): array
    {
        app(PayCapitalSubscription::class)->handle($member->capital_subscriptions_common, new CapitalSubscriptionPaymentData(
            payment_type_id: $payment_type->id,
            reference_number: $reference_number,
            amount: $amount,
            transaction_date: $transaction_date,
        ), $transaction_type);
        return [
            'account_number' => $member->capital_subscription_account->number,
            'account_name' => $member->capital_subscription_account->name,
            'reference_number' => $reference_number,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => 'CBU PAYMENT'
        ];
    }
}