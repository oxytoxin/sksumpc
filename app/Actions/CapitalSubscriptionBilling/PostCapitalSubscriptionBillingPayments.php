<?php

namespace App\Actions\CapitalSubscriptionBilling;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Models\CapitalSubscriptionBilling;
use App\Models\CapitalSubscriptionBillingPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PostCapitalSubscriptionBillingPayments
{
    public function handle(CapitalSubscriptionBilling $cbuBilling)
    {
        if (! $cbuBilling->reference_number || ! $cbuBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transaction_type = TransactionType::CRJ();
        $cbuBilling->capital_subscription_billing_payments()->with('capital_subscription.member.capital_subscription_account')->each(function (CapitalSubscriptionBillingPayment $cbup) use ($cbuBilling, $transaction_type) {
            app(PayCapitalSubscription::class)->handle($cbup->capital_subscription, new TransactionData(
                account_id: $cbup->capital_subscription->member->capital_subscription_account->id,
                transactionType: $transaction_type,
                reference_number: $cbuBilling->or_number,
                payment_type_id: $cbuBilling->payment_type_id,
                credit: $cbup->amount_paid,
                member_id: $cbup->capital_subscription->member_id,
                transaction_date: $cbuBilling->date,
                payee: $cbup->capital_subscription->member->full_name,
            ));
            $cbup->update([
                'posted' => true,
            ]);
        });
        $cbuBilling->update([
            'posted' => true,
        ]);
        DB::commit();
    }
}
