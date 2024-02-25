<?php

namespace App\Actions\CapitalSubscription;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\CapitalSubscription;
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
        $payment = $cbu->payments()->create($data->toArray());
        if ($transact) {
            if ($data->payment_type_id == 1) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashOnHand()->id,
                    transactionType: $transactionType,
                    reference_number: $payment->reference_number,
                    debit: $payment->amount,
                    member_id: $cbu->member->id,
                    remarks: 'Member CBU Payment'
                ));
            }
            if ($data->payment_type_id == 4) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashInBankGF()->id,
                    transactionType: $transactionType,
                    reference_number: $payment->reference_number,
                    debit: $payment->amount,
                    member_id: $cbu->member->id,
                    remarks: 'Member CBU Payment'
                ));
            }
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $cbu->member->capital_subscription_account->id,
                transactionType: $transactionType,
                reference_number: $payment->reference_number,
                credit: $payment->amount,
                member_id: $cbu->member->id,
                remarks: 'Member CBU Payment'
            ));
        }
        DB::commit();
    }
}
