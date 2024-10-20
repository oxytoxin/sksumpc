<?php

namespace App\Actions\LoveGifts;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\LoveGift;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\LoveGiftProvider;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToLoveGiftsAccount
{
    use AsAction;

    public function handle(Member $member, LoveGiftData $data, TransactionType $transactionType, $isJevOrDv = false)
    {
        DB::beginTransaction();
        $love_gift_account = $member->love_gift_account;
        $love_gift = LoveGift::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => LoveGiftProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        if (!$isJevOrDv) {
            if ($data->payment_type_id == 1) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashOnHand()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $love_gift->reference_number,
                    debit: $love_gift->amount,
                    member_id: $love_gift->member_id,
                    remarks: 'Member Deposit to Love Gift',
                ));
            }
            if ($data->payment_type_id == 4) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getCashInBankMSO()->id,
                    transactionType: $transactionType,
                    payment_type_id: $data->payment_type_id,
                    reference_number: $love_gift->reference_number,
                    debit: $love_gift->amount,
                    member_id: $love_gift->member_id,
                    remarks: 'Member Deposit to Love Gift',
                ));
            }
        }

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $love_gift_account->id,
            transactionType: $transactionType,
            payment_type_id: $data->payment_type_id,
            reference_number: $love_gift->reference_number,
            credit: $love_gift->amount,
            member_id: $member->id,
            remarks: 'Member Deposit to Love Gifts',
            tag: 'member_love_gift_deposit',
        ));
        DB::commit();

        return $love_gift;
    }
}
