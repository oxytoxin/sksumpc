<?php

namespace App\Actions\CapitalSubscriptionBilling;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Models\CapitalSubscriptionBilling;
use App\Models\CapitalSubscriptionBillingPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class PostCapitalSubscriptionBillingPayments
{
    use AsAction;

    public function handle(CapitalSubscriptionBilling $cbuBilling)
    {
        if (!$cbuBilling->reference_number || !$cbuBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transaction_type = TransactionType::firstWhere('name', 'CRJ');
        $cbuBilling->capital_subscription_billing_payments()->with('capital_subscription')->each(function (CapitalSubscriptionBillingPayment $cbup) use ($cbuBilling, $transaction_type) {
            app(PayCapitalSubscription::class)->handle($cbup->capital_subscription, new CapitalSubscriptionPaymentData(
                payment_type_id: $cbuBilling->payment_type_id,
                reference_number: $cbuBilling->or_number,
                amount: $cbup->amount_paid,
                transaction_date: $cbuBilling->date,
            ), $transaction_type);
            $cbup->update([
                'posted' => true,
            ]);
        });
        $cbuBilling->update([
            'posted' => true,
        ]);
        DB::commit();
        Notification::make()->title('Payments posted!')->success()->send();
    }
}
