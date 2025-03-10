<?php

namespace App\Actions\CapitalSubscriptionBilling;

use App\Models\Account;
use App\Enums\PaymentTypes;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Models\CapitalSubscriptionBilling;
use App\Actions\Transactions\CreateTransaction;
use App\Models\CapitalSubscriptionBillingPayment;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Actions\CapitalSubscription\PayCapitalSubscription;

class PostCapitalSubscriptionBillingPayments
{
    public function handle(CapitalSubscriptionBilling $cbuBilling)
    {
        if (! $cbuBilling->reference_number || ! $cbuBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transactionType = TransactionType::CRJ();
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        $cbuBilling->capital_subscription_billing_payments()->each(function (CapitalSubscriptionBillingPayment $cbup) use ($cbuBilling, $transactionType, $cash_in_bank_account_id, $cash_on_hand_account_id) {
            $data = new TransactionData(
                account_id: $cbup->member->capital_subscription_account->id,
                transactionType: $transactionType,
                reference_number: $cbuBilling->or_number,
                payment_type_id: $cbuBilling->payment_type_id,
                credit: $cbup->amount_paid,
                member_id: $cbup->member_id,
                transaction_date: $cbuBilling->or_date ?? $cbuBilling->date,
                payee: $cbup->member->full_name,
            );
            app(PayCapitalSubscription::class)->handle($cbup->capital_subscription, $data);

            if ($data->payment_type_id == PaymentTypes::ADA->value) {
                $data->account_id = $cash_in_bank_account_id;
            } else {
                $data->account_id = $cash_on_hand_account_id;
            }
            $data->debit = $cbup->amount_paid;
            $data->credit = null;
            app(CreateTransaction::class)->handle($data);

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
