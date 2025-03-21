<?php

namespace App\Actions\Transactions;

use App\Models\Transaction;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CreateTransaction
{
    public function handle(TransactionData $transactionData)
    {
        return Transaction::create([
            'account_id' => $transactionData->account_id,
            'member_id' => $transactionData->member_id,
            'payment_type_id' => $transactionData->payment_type_id,
            'reference_number' => $transactionData->reference_number,
            'debit' => $transactionData->debit,
            'credit' => $transactionData->credit,
            'transaction_type_id' => $transactionData->transactionType->id,
            'remarks' => $transactionData->remarks,
            'tag' => $transactionData->tag,
            'payee' => $transactionData->payee,
            'transaction_date' => $transactionData->transaction_date,
            'from_billing_type' => $transactionData->from_billing_type,
        ]);
    }
}
