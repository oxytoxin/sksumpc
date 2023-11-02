<?php

namespace App\Oxytoxin;

use App\Models\Member;
use App\Models\Saving;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class SavingsProvider
{
    const INTEREST_RATE = 0.01;
    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;

    public static function calculateInterest($amount, $interest, $days)
    {
        if ($amount < static::MINIMUM_AMOUNT_FOR_INTEREST) {
            return 0;
        }

        return $amount * $interest * $days / 365;
    }

    public static function createSavings(Member $member, SavingsData $data)
    {
        $member->savings_no_interest()->each(function ($saving) use ($data) {
            $saving->update([
                'interest' => SavingsProvider::calculateInterest($saving->balance, $saving->interest_rate, Carbon::make($data->transaction_date)->diffInDays($saving->transaction_date)),
                'interest_date' => $data->transaction_date,
            ]);
        });
        $isWithdrawal = $data->amount < 0;
        if ($isWithdrawal && $member->savings()->sum('amount') + $data->amount < 500) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.'
            ]);
        }
        return Saving::create([
            'transaction_date' => $data->transaction_date,
            'type' => $data->type,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'balance' => $member->savings()->sum('amount') + $data->amount,
        ]);
    }
}
