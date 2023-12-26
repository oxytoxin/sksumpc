<?php

namespace App\Oxytoxin;

use App\Models\Imprest;
use App\Models\Member;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ImprestsProvider
{
    const INTEREST_RATE = 0.02;

    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;

    const FROM_TRANSFER_CODE = '#TRANSFERFROMIMPRESTS';

    public static function calculateInterest($amount, $interest, $days)
    {
        if ($amount < static::MINIMUM_AMOUNT_FOR_INTEREST) {
            return 0;
        }

        return $amount * $interest * $days / 365;
    }

    public static function createImprest(Member $member, ImprestData $data)
    {
        $member->imprests_no_interest()->each(function ($imprest) use ($data) {
            $imprest->update([
                'interest' => static::calculateInterest($imprest->balance, $imprest->interest_rate, Carbon::make($data->transaction_date)->diffInDays($imprest->transaction_date)),
                'interest_date' => $data->transaction_date,
            ]);
        });
        $isWithdrawal = $data->amount < 0;
        if ($isWithdrawal && ($member->imprests()->sum('amount') + $data->amount < 500)) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }

        return Imprest::create([
            'transaction_date' => $data->transaction_date,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $member->id,
        ]);
    }
}
