<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Models\ImprestAccount;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\Providers\ImprestsProvider;

class CashierTransactionsPageImprests
{

    public static function handle($is_deposit, Member $member, ImprestAccount $imprest_account, TransactionType $transaction_type, $payment_type, $reference_number, $amount, $transaction_date)
    {
        if ($is_deposit) {
            app(DepositToImprestAccount::class)->handle($member, new ImprestData(
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                amount: $amount,
                transaction_date: $transaction_date,
            ), $transaction_type);
        } else {
            app(WithdrawFromImprestAccount::class)->handle($member, new ImprestData(
                payment_type_id: $payment_type->id,
                reference_number: ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
                amount: $amount,
                transaction_date: $transaction_date,
            ), $transaction_type);
        }
        return [
            'account_number' => $imprest_account->number,
            'account_name' => $imprest_account->name,
            'reference_number' => $is_deposit ? $reference_number : ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => $is_deposit ? 'IMPREST DEPOSIT' : 'IMPREST WITHDRAWAL'
        ];
    }
}