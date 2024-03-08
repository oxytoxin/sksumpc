<?php

namespace App\Actions\Transactions;

use App\Models\Transaction;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTransaction
{
    use AsAction;

    public function handle(TransactionData $transactionData)
    {
        return Transaction::create([
            'account_id' => $transactionData->account_id,
            'member_id' => $transactionData->member_id,
            'reference_number' => $transactionData->reference_number,
            'debit' => $transactionData->debit,
            'credit' => $transactionData->credit,
            'transaction_type_id' => $transactionData->transactionType->id,
            'remarks' => $transactionData->remarks,
            'tag' => $transactionData->tag,
        ]);
    }
}
