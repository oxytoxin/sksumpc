<?php

namespace App\Actions\CashCollectionBilling;

use App\Actions\Transactions\CreateTransaction;
use App\Enums\FromBillingTypes;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\CashCollectibleBilling;
use App\Models\CashCollectibleBillingPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PostCashCollectibleBillingPayments
{
    public function handle(CashCollectibleBilling $cashCollectibleBilling)
    {
        if (! $cashCollectibleBilling->reference_number || ! $cashCollectibleBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transactionType = TransactionType::CRJ();
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        $cashCollectibleBilling->cash_collectible_billing_payments()->each(function (CashCollectibleBillingPayment $payment) use ($cashCollectibleBilling, $transactionType, $cash_in_bank_account_id, $cash_on_hand_account_id) {
            $data = new TransactionData(
                account_id: $cashCollectibleBilling->cash_collectible_account->id,
                transactionType: $transactionType,
                reference_number: $cashCollectibleBilling->or_number,
                payment_type_id: $cashCollectibleBilling->payment_type_id,
                credit: $payment->amount_paid,
                member_id: $payment->member_id,
                transaction_date: $cashCollectibleBilling->or_date ?? $cashCollectibleBilling->date,
                payee: $payment->payee,
                from_billing_type: FromBillingTypes::CASH_COLLECTIBLE_BILLING->value
            );

            app(CreateTransaction::class)->handle($data);

            $data->debit = $data->credit;
            $data->credit = null;

            if ($data->payment_type_id == PaymentTypes::ADA->value) {
                $data->account_id = $cash_in_bank_account_id;
            } else {
                $data->account_id = $cash_on_hand_account_id;
            }
            app(CreateTransaction::class)->handle($data);

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
