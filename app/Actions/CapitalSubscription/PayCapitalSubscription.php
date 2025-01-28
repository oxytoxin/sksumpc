<?php

namespace App\Actions\CapitalSubscription;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\CapitalSubscription;
use App\Models\CapitalSubscriptionPayment;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;

class PayCapitalSubscription
{
    public function handle(CapitalSubscription $cbu, TransactionData $transactionData, $autodeposit = true)
    {
        $transactionData = clone $transactionData;
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
        if ($autodeposit && $amount > 0) {
            $deposit = fmod($amount, $cbu->par_value);
            if ($deposit > 0)
                $transactionData->credit = $amount - $deposit;
            app(CreateTransaction::class)->handle($transactionData);
            if ($deposit > 0) {
                $transactionData->credit = $deposit;
                $transactionData->account_id = Account::getCbuDeposit($cbu->member->member_type_id)->id;
                app(CreateTransaction::class)->handle($transactionData);
            }
        } else {
            app(CreateTransaction::class)->handle($transactionData);
        }

        DB::commit();

        return $payment;
    }
}
