<?php

namespace App\Oxytoxin;

use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class SavingsProvider
{
    const INTEREST_RATE = 0.01;

    const MINIMUM_AMOUNT_FOR_INTEREST = 1000;

    const FROM_TRANSFER_CODE = '#TRANSFERFROMSAVINGS';

    public static function calculateInterest($amount, $interest, $days)
    {
        if ($amount < static::MINIMUM_AMOUNT_FOR_INTEREST) {
            return 0;
        }

        return $amount * $interest * $days / 365;
    }

    public static function createSavings(Member $member, SavingsData $data)
    {
        $savings_account = SavingsAccount::find($data->savings_account_id);
        $member->savings_no_interest()->where('savings_account_id', $data->savings_account_id)->each(function ($saving) use ($data) {
            $saving->update([
                'interest' => SavingsProvider::calculateInterest($saving->balance, $saving->interest_rate, Carbon::make($data->transaction_date)->diffInDays($saving->transaction_date)),
                'interest_date' => $data->transaction_date,
            ]);
        });

        $isWithdrawal = $data->amount < 0;
        if ($isWithdrawal && ($savings_account->balance + $data->amount < 500)) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }

        return Saving::create([
            'savings_account_id' => $data->savings_account_id,
            'transaction_date' => $data->transaction_date,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $member->id,
        ]);
    }
}
