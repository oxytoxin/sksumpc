<?php

namespace App\Actions\TimeDeposits;

use App\Actions\Transactions\CreateTransaction;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use DB;

class RolloverTimeDeposit
{
    public function handle(TimeDeposit $time_deposit, $interest_rate, $number_of_days, $reference_number, $payment_type_id = PaymentTypes::CASH->value)
    {
        DB::beginTransaction();

        $new_time_deposit = $time_deposit->replicate(['identifier', 'interest']);
        $time_deposit->update([
            'withdrawal_date' => config('app.transaction_date') ?? today(),
        ]);
        $data = new TransactionData(
            account_id: $time_deposit->time_deposit_account_id,
            transactionType: TransactionType::CRJ(),
            payment_type_id: 1,
            reference_number: $time_deposit->reference_number,
            credit: $time_deposit->interest,
            member_id: $time_deposit->member_id,
            remarks: 'Member Time Deposit Rollover',
            tag: 'member_time_deposit',
        );

        app(CreateTransaction::class)->handle($data);

        $data->debit = $data->credit;
        $data->credit = null;
        $data->account_id = Account::getTimeDepositInterestExpense()->id;
        $data->remarks = 'Member Time Deposit Rollover Interest Expense';

        app(CreateTransaction::class)->handle($data);

        $new_time_deposit->payment_type_id = $payment_type_id;
        $new_time_deposit->interest_rate = $interest_rate;
        $new_time_deposit->number_of_days = $number_of_days;
        $new_time_deposit->reference_number = $reference_number;
        $new_time_deposit->transaction_date = config('app.transaction_date') ?? today();
        $new_time_deposit->amount = $time_deposit->maturity_amount;
        $new_time_deposit->maturity_amount = TimeDepositsProvider::getMaturityAmount($time_deposit->maturity_amount, $interest_rate, $number_of_days);
        $new_time_deposit->maturity_date = (config('app.transaction_date') ?? today())->addDays($number_of_days);
        $new_time_deposit->save();

        DB::commit();
    }
}
