<?php

namespace App\Actions\CapitalSubscription;

use App\Actions\Transactions\CreateTransaction;
use App\Models\CapitalSubscription;
use App\Models\CapitalSubscriptionPayment;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;

class PayCapitalSubscription
{
    public function handle(CapitalSubscription $cbu, TransactionData $transactionData)
    {
        DB::beginTransaction();
        $amount = $transactionData->credit > 0 ? $transactionData->credit : $transactionData->debit * -1;
        $payment = CapitalSubscriptionPayment::create([
            'capital_subscription_id' => $cbu->id,
            'member_id' => $cbu->member_id,
            'payment_type_id' => $transactionData->payment_type_id,
            'reference_number' => $transactionData->reference_number,
            'amount' => $amount,
            'transaction_date' => $transactionData->transaction_date,
        ]);
        app(CreateTransaction::class)->handle($transactionData);
        DB::commit();
    }
}
