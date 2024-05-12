<?php

namespace App\Actions\CashCollectionBilling;

use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Actions\CashCollections\PayCashCollectible;
use App\Models\CashCollectibleBilling;
use App\Models\CashCollectibleBillingPayment;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use Lorisleiva\Actions\Concerns\AsAction;

class PostCashCollectibleBillingPayments
{
    use AsAction;

    public function handle(CashCollectibleBilling $cashCollectibleBilling)
    {
        if (!$cashCollectibleBilling->reference_number || !$cashCollectibleBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transaction_type = TransactionType::firstWhere('name', 'CRJ');
        $cashCollectibleBilling->cash_collectible_billing_payments()->each(function (CashCollectibleBillingPayment $payment) use ($cashCollectibleBilling, $transaction_type) {
            app(PayCashCollectible::class)->handle(
                cashCollectible: $cashCollectibleBilling->cash_collectible,
                cashCollectiblePaymentData: new CashCollectiblePaymentData(
                    member_id: $payment->member_id,
                    payment_type_id: $cashCollectibleBilling->payment_type_id,
                    reference_number: $cashCollectibleBilling->or_number,
                    amount: $payment->amount_paid,
                    payee: $payment->payee,
                    transaction_date: $cashCollectibleBilling->date
                ),
                transactionType: $transaction_type
            );
            $payment->update([
                'posted' => true,
            ]);
        });

        $cashCollectibleBilling->update([
            'posted' => true,
        ]);
        DB::commit();
    }
}
