<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Models\LoveGiftAccount;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\Providers\LoveGiftProvider;

class CashierTransactionsPageLoveGifts
{
    public static function handle($is_deposit, Member $member, LoveGiftAccount $love_gift_account, TransactionType $transaction_type, PaymentType $payment_type, $reference_number, $amount, $transaction_date)
    {
        if ($is_deposit) {
            app(DepositToLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                payment_type_id: $payment_type->id,
                reference_number: $reference_number,
                amount: $amount,
                transaction_date: $transaction_date,
            ), $transaction_type);
        } else {
            app(WithdrawFromLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                payment_type_id: $payment_type->id,
                reference_number: LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE,
                amount: $amount,
                transaction_date: $transaction_date,
            ), $transaction_type);
        }

        return [
            'account_number' => $love_gift_account->number,
            'account_name' => $love_gift_account->name,
            'reference_number' => $is_deposit ? $reference_number : LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE,
            'amount' => $amount,
            'payment_type' => $payment_type->name ?? 'CASH',
            'remarks' => $is_deposit ? 'LOVE GIFT DEPOSIT' : 'LOVE GIFT WITHDRAWAL',
        ];
    }
}
