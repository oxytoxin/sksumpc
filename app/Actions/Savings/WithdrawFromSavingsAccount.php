<?php

namespace App\Actions\Savings;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use DB;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class WithdrawFromSavingsAccount
{
    public function handle(Member $member, TransactionData $data)
    {
        $data->remarks = 'Member Withdrawal from Savings';
        $data->tag = 'member_savings_withdrawal';
        DB::beginTransaction();
        $savings_account = SavingsAccount::find($data->account_id);
        if ($savings_account->savings()->sum('amount') - $data->debit < 500) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }

        $savings = Saving::create([
            'savings_account_id' => $data->account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit * -1,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);

        app(CreateTransaction::class)->handle($data);
        DB::commit();

        return $savings;
    }
}
