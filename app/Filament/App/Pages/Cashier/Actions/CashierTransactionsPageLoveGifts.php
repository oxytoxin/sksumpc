<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Enums\MsoType;
use App\Models\LoveGiftAccount;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CashierTransactionsPageLoveGifts
{
    public static function handle($is_deposit, Member $member, LoveGiftAccount $love_gift_account, TransactionType $transaction_type, PaymentType $payment_type, $reference_number, $amount, $transaction_date)
    {
        if ($is_deposit) {
            $love_gift = app(DepositToMsoAccount::class)->handle(MsoType::LOVE_GIFT, new TransactionData(
                account_id: $love_gift_account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                credit: $amount,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $transaction_date,
            ));
        } else {
            $love_gift = app(WithdrawFromMsoAccount::class)->handle(MsoType::LOVE_GIFT, new TransactionData(
                account_id: $love_gift_account->id,
                transactionType: $transaction_type,
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                debit: $amount,
                member_id: $member->id,
                payee: $member->full_name,
                transaction_date: $transaction_date,
            ));

            $love_gift->revolving_fund()->create([
                'reference_number' => $love_gift->reference_number,
                'withdrawal' => $amount,
                'transaction_date' => $love_gift->transaction_date,
            ]);
        }

        return [
            'account_number' => $love_gift_account->number,
            'account_name' => $love_gift_account->name,
            'reference_number' => $love_gift->reference_number,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => $is_deposit ? 'LOVE GIFT DEPOSIT' : 'LOVE GIFT WITHDRAWAL',
        ];
    }
}
