<?php

namespace App\Actions\TimeDeposits;

use App\Actions\Transactions\CreateTransaction;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use DB;

class TerminateTimeDeposit
{
    public function handle(TimeDeposit $time_deposit)
    {
        DB::beginTransaction();
        $time_deposit->update([
            'maturity_amount' => TimeDepositsProvider::getMaturityAmount($time_deposit->amount, TimeDepositsProvider::TERMINATION_INTEREST_RATE, $time_deposit->transaction_date->diffInDays(config('app.transaction_date') ?? today())),
            'withdrawal_date' => config('app.transaction_date') ?? today(),
        ]);

        $time_deposit->refresh();

        if ($time_deposit->interest > 0) {
            $data = new TransactionData(
                account_id: $time_deposit->time_deposit_account_id,
                transactionType: TransactionType::CRJ(),
                payment_type_id: PaymentTypes::JEV->value,
                reference_number: $time_deposit->reference_number,
                credit: $time_deposit->interest,
                member_id: $time_deposit->member_id,
                remarks: 'Member Time Deposit Termination',
                tag: 'member_time_deposit',
            );

            app(CreateTransaction::class)->handle($data);

            $data->debit = $data->credit;
            $data->credit = null;
            $data->account_id = Account::getTimeDepositInterestExpense()->id;
            $data->remarks = 'Member Time Deposit Termination Interest Expense';

            app(CreateTransaction::class)->handle($data);
        }


        DB::commit();
    }
}
