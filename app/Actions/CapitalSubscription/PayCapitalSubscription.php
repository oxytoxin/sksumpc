<?php

namespace App\Actions\CapitalSubscription;

use App\Models\CapitalSubscription;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class PayCapitalSubscription
{
    use AsAction;

    public function handle(CapitalSubscription $cbu, CapitalSubscriptionPaymentData $data, TransactionType $transactionType)
    {
        DB::beginTransaction();
        $payment = $cbu->payments()->create($data->toArray());
        $cbu->member->capital_subscription_account->transactions()->create([
            'transaction_type_id' => $transactionType->id,
            'reference_number' => $payment->reference_number,
            'credit' => $payment->amount,
        ]);
        DB::commit();
    }
}
