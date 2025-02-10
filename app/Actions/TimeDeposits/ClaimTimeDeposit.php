<?php

namespace App\Actions\TimeDeposits;

use App\Models\Account;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Actions\Transactions\CreateTransaction;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class ClaimTimeDeposit
{
    public function handle(TimeDeposit $time_deposit)
    {
        DB::beginTransaction();
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
            remarks: 'Member Time Deposit Withdrawal',
            tag: 'member_time_deposit',
        );

        app(CreateTransaction::class)->handle($data);

        $data->debit = $data->credit;
        $data->credit = null;
        $data->account_id = Account::getTimeDepositInterestExpense()->id;
        $data->remarks = 'Member Time Deposit Withdrawal Interest Expense';

        app(CreateTransaction::class)->handle($data);

        DB::commit();
    }
}
