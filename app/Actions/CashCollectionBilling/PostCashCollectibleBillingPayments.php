<?php

namespace App\Actions\CashCollectionBilling;

use App\Models\Account;
use App\Enums\PaymentTypes;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Models\CashCollectibleBilling;
use Filament\Notifications\Notification;
use App\Models\CashCollectibleBillingPayment;
use App\Actions\Transactions\CreateTransaction;
use App\Actions\CashCollections\PayCashCollectible;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;

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
                account_id: $cash_in_bank_account_id,
                transactionType: $transactionType,
                reference_number: $cashCollectibleBilling->or_number,
                payment_type_id: $cashCollectibleBilling->payment_type_id,
                debit: $payment->amount_paid,
                member_id: $payment->member_id,
                transaction_date: $cashCollectibleBilling->date,
                payee: $payment->payee,
            );

            app(PayCashCollectible::class)->handle(
                cashCollectibleAccount: $cashCollectibleBilling->cash_collectible_account,
                cashCollectiblePaymentData: new CashCollectiblePaymentData(
                    member_id: $payment->member_id,
                    payment_type_id: $cashCollectibleBilling->payment_type_id,
                    reference_number: $cashCollectibleBilling->or_number,
                    amount: $payment->amount_paid,
                    payee: $payment->payee,
                    transaction_date: $cashCollectibleBilling->date
                ),
                transactionType: $transactionType
            );

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
