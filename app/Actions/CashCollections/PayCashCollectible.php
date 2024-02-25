<?php

namespace App\Actions\CashCollections;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\CashCollectible;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
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

        if ($cashCollectiblePaymentData->payment_type_id == 1) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashOnHand()->id,
                transactionType: $transactionType,
                reference_number: $payment->reference_number,
                debit: $payment->amount,
                member_id: $payment->member_id,
                remarks: 'Member Payment for '.strtoupper($cashCollectible->name),
            ));
        }
        if ($cashCollectiblePaymentData->payment_type_id == 4) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashInBankGF()->id,
                transactionType: $transactionType,
                reference_number: $payment->reference_number,
                debit: $payment->amount,
                member_id: $payment->member_id,
                remarks: 'Member Payment for '.strtoupper($cashCollectible->name),
            ));
        }

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $accounts_receivables_account->id,
            transactionType: $transactionType,
            reference_number: $payment->reference_number,
            credit: $payment->amount,
            member_id: $payment->member_id,
            remarks: 'Member Payment for '.strtoupper($cashCollectible->name),
        ));
        DB::commit();

        return $payment;
    }
}
