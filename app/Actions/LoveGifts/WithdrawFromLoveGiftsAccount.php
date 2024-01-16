<?php

namespace App\Actions\LoveGifts;

use App\Models\LoveGift;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\Providers\LoveGiftProvider;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class WithdrawFromLoveGiftsAccount
{
    use AsAction;

    public function handle(Member $member, LoveGiftData $data)
    {
        if ($member->love_gifts()->sum('amount') - $data->amount < 500) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }

        return LoveGift::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount * -1,
            'interest_rate' => LoveGiftProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
    }
}
