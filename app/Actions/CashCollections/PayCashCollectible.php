<?php

namespace App\Actions\CashCollections;

use App\Models\Account;
use App\Models\CashCollectible;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class PayCashCollectible
{
    use AsAction;

    public function handle(CashCollectible $cashCollectible, CashCollectiblePaymentData $cashCollectiblePaymentData, TransactionType $transactionType)
    {
        DB::beginTransaction();
        $payment = $cashCollectible->payments()->create([
            'payee' => $cashCollectiblePaymentData->payee,
            'payment_type_id' => $cashCollectiblePaymentData->payment_type_id,
            'reference_number' => $cashCollectiblePaymentData->reference_number,
            'amount' => $cashCollectiblePaymentData->amount,
            'member_id' => $cashCollectiblePaymentData->member_id,
        ]);
        $accounts_receivables_account = Account::whereTag('account_receivables')->whereAccountableType(CashCollectible::class)->whereAccountableId($cashCollectible->id)->first();
        $accounts_receivables_account->transactions()->create([
            'reference_number' => $payment->reference_number,
            'credit' => $payment->amount,
            'transaction_type_id' => $transactionType->id,
        ]);
        DB::commit();
        return $payment;
    }
}
