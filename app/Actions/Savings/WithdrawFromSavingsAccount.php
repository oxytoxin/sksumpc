<?php

namespace App\Actions\Savings;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use DB;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class WithdrawFromSavingsAccount
{
    use AsAction;

    public function handle(Member $member, SavingsData $data, TransactionType $transactionType)
    {
        DB::beginTransaction();
        $savings_account = SavingsAccount::find($data->savings_account_id);
        if ($savings_account->savings()->sum('amount') - $data->amount < 500) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }

        $savings = Saving::create([
            'savings_account_id' => $data->savings_account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount * -1,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        if ($data->payment_type_id == 1) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashOnHand()->id,
                transactionType: $transactionType,
                reference_number: $savings->reference_number,
                credit: $savings->amount,
                member_id: $savings->member_id,
                remarks: 'Member Withdrawal from Savings',
            ));
        }
        if ($data->payment_type_id == 4) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashInBankMSO()->id,
                transactionType: $transactionType,
                reference_number: $savings->reference_number,
                credit: $savings->amount,
                member_id: $savings->member_id,
                remarks: 'Member Withdrawal from Savings',
            ));
        }
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $savings_account->id,
            transactionType: $transactionType,
            reference_number: $savings->reference_number,
            debit: $savings->amount,
            member_id: $member->id,
            remarks: 'Member Withdrawal from Savings'
        ));
        DB::commit();

        return $savings;
    }
}
