<?php

namespace App\Actions\LoveGifts;

use App\Models\LoveGift;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\LoveGiftProvider;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToLoveGiftsAccount
{
    use AsAction;

    public function handle(Member $member, LoveGiftData $data)
    {
        return LoveGift::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => LoveGiftProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
    }
}
