<?php

namespace App\Actions\CapitalSubscription;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\CapitalSubscription;
use App\Models\CapitalSubscriptionPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class PayCapitalSubscription
{
    use AsAction;

    public function handle(CapitalSubscription $cbu, CapitalSubscriptionPaymentData $data, TransactionType $transactionType, $transact = true)
    {
        DB::beginTransaction();
        $payment = CapitalSubscriptionPayment::create([
            'capital_subscription_id' => $cbu->id,
            'member_id' => $cbu->member_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'transaction_date' => $data->transaction_date
        ]);
        if ($transact) {
            if ($data->payment_type_id == 1) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashOnHand()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $payment->reference_number,
                    debit: $payment->amount,
                    member_id: $cbu->member->id,
                    remarks: 'Member CBU Payment',
                    transaction_date: $data->transaction_date,
                ));
            }
            if ($data->payment_type_id == 4) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashInBankGF()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $payment->reference_number,
                    debit: $payment->amount,
                    member_id: $cbu->member->id,
                    remarks: 'Member CBU Payment',
                    transaction_date: $data->transaction_date,
                ));
            }
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $cbu->member->capital_subscription_account->id,
                transactionType: $transactionType,
                payment_type_id: $data->payment_type_id,
                reference_number: $payment->reference_number,
                credit: $payment->amount,
                member_id: $cbu->member->id,
                remarks: 'Member CBU Payment',
                transaction_date: $data->transaction_date,
            ));
        }
        DB::commit();
    }
}
