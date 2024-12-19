<?php

namespace App\Actions\CashCollections;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\CashCollectible;
use App\Models\CashCollectibleAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;


class PayCashCollectible
{


    public function handle(CashCollectibleAccount $cashCollectibleAccount, CashCollectiblePaymentData $cashCollectiblePaymentData, TransactionType $transactionType)
    {
        DB::beginTransaction();
        if ($cashCollectiblePaymentData->payment_type_id == 1) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashOnHand()->id,
                transactionType: $transactionType,
                payment_type_id: $cashCollectiblePaymentData->payment_type_id,
                reference_number: $cashCollectiblePaymentData->reference_number,
                debit: $cashCollectiblePaymentData->amount,
                member_id: $cashCollectiblePaymentData->member_id,
                payee: $cashCollectiblePaymentData->payee,
                remarks: 'Payment for ' . strtoupper($cashCollectibleAccount->name),
                transaction_date: $cashCollectiblePaymentData->transaction_date
            ));
        }
        if ($cashCollectiblePaymentData->payment_type_id == 4) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashInBankGF()->id,
                transactionType: $transactionType,
                payment_type_id: $cashCollectiblePaymentData->payment_type_id,
                reference_number: $cashCollectiblePaymentData->reference_number,
                debit: $cashCollectiblePaymentData->amount,
                member_id: $cashCollectiblePaymentData->member_id,
                payee: $cashCollectiblePaymentData->payee,
                remarks: 'Payment for ' . strtoupper($cashCollectibleAccount->name),
                transaction_date: $cashCollectiblePaymentData->transaction_date
            ));
        }
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $cashCollectibleAccount->id,
            transactionType: $transactionType,
            payment_type_id: $cashCollectiblePaymentData->payment_type_id,
            reference_number: $cashCollectiblePaymentData->reference_number,
            credit: $cashCollectiblePaymentData->amount,
            member_id: $cashCollectiblePaymentData->member_id,
            payee: $cashCollectiblePaymentData->payee,
            remarks: 'Payment for ' . strtoupper($cashCollectibleAccount->name),
            transaction_date: $cashCollectiblePaymentData->transaction_date
        ));
        DB::commit();
    }
}
