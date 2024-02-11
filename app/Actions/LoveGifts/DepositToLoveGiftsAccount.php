<?php

namespace App\Actions\LoveGifts;

use App\Models\LoveGift;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\Providers\LoveGiftProvider;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToLoveGiftsAccount
{
    use AsAction;

    public function handle(Member $member, LoveGiftData $data, TransactionType $transactionType)
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
        $love_gift_account->transactions()->create([
            'transaction_type_id' => $transactionType->id,
            'reference_number' => $love_gift->reference_number,
            'credit' => $love_gift->amount,
        ]);
        DB::commit();

        return $love_gift;
    }
}
