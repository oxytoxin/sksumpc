<?php

namespace App\Actions\TimeDeposits;

use App\Actions\Transactions\CreateTransaction;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolloverMaturedTimeDepositsAction
{
    public function handle(?string $dateToClose = null): array
    {
        $targetDate = $dateToClose ? CarbonImmutable::parse($dateToClose) : config('app.transaction_date');

        $maturedDeposits = TimeDeposit::query()
            ->where('maturity_date', '<=', $targetDate)
            ->whereNull('withdrawal_date')
            ->whereNull('auto_rolled_over_at')
            ->get();

        if ($maturedDeposits->isEmpty()) {
            return ['successful' => 0, 'failed' => 0];
        }

        $successfulRollOvers = 0;
        $failedRollOvers = 0;

        foreach ($maturedDeposits as $timeDeposit) {
            try {
                DB::beginTransaction();

                $newTimeDeposit = $timeDeposit->replicate(['identifier', 'interest']);
                $timeDeposit->update([
                    'withdrawal_date' => $timeDeposit->maturity_date,
                ]);

                $data = new TransactionData(
                    account_id: $timeDeposit->time_deposit_account_id,
                    transactionType: TransactionType::JEV(),
                    payment_type_id: PaymentTypes::JEV->value,
                    reference_number: $timeDeposit->reference_number,
                    credit: $timeDeposit->interest,
                    member_id: $timeDeposit->member_id,
                    remarks: 'Member Time Deposit Auto-Rollover',
                    tag: 'member_time_deposit',
                );

                app(CreateTransaction::class)->handle($data);

                $data->debit = $data->credit;
                $data->credit = null;
                $data->account_id = Account::getTimeDepositInterestExpense()->id;
                $data->remarks = 'Member Time Deposit Auto-Rollover Interest Expense';

                app(CreateTransaction::class)->handle($data);

                $newTimeDeposit->payment_type_id = PaymentTypes::JEV->value;
                $newTimeDeposit->interest_rate = $timeDeposit->interest_rate;
                $newTimeDeposit->number_of_days = $timeDeposit->number_of_days;
                $newTimeDeposit->reference_number = $timeDeposit->reference_number;
                $newTimeDeposit->transaction_date = $timeDeposit->maturity_date;
                $newTimeDeposit->amount = $timeDeposit->maturity_amount;
                $newTimeDeposit->maturity_amount = TimeDepositsProvider::getMaturityAmount($timeDeposit->maturity_amount, $timeDeposit->interest_rate, $timeDeposit->number_of_days);
                $newTimeDeposit->maturity_date = $timeDeposit->maturity_date->addDays($timeDeposit->number_of_days);
                $newTimeDeposit->cashier_id = null;
                $newTimeDeposit->save();

                $timeDeposit->update([
                    'auto_rolled_over_at' => $targetDate,
                ]);

                DB::commit();

                $successfulRollOvers++;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Time deposit rollover failed', [
                    'time_deposit_id' => $timeDeposit->id,
                    'reference_number' => $timeDeposit->reference_number,
                    'error' => $e->getMessage(),
                ]);
                $failedRollOvers++;
            }
        }

        return ['successful' => $successfulRollOvers, 'failed' => $failedRollOvers];
    }
}
