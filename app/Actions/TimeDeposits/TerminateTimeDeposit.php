<?php

namespace App\Actions\TimeDeposits;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;

class TerminateTimeDeposit
{
    public function handle(TimeDeposit $timeDeposit, $withdrawal_date)
    {
        $timeDeposit->update([
            'maturity_amount' => TimeDepositsProvider::getMaturityAmount($timeDeposit->amount, SavingsProvider::INTEREST_RATE),
            'withdrawal_date' => $withdrawal_date,
        ]);

        $timeDeposit->refresh();

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: Account::getCashInBankMSO()->id,
            transactionType: TransactionType::CRJ(),
            payment_type_id: 1,
            reference_number: $timeDeposit->reference_number,
            credit: $timeDeposit->maturity_amount,
            member_id: $timeDeposit->member_id,
            remarks: 'Member Time Deposit Termination'
        ));

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $timeDeposit->time_deposit_account_id,
            transactionType: TransactionType::CRJ(),
            payment_type_id: 1,
            reference_number: $timeDeposit->reference_number,
            debit: $timeDeposit->maturity_amount,
            member_id: $timeDeposit->member_id,
            remarks: 'Member Time Deposit Termination',
            tag: 'member_time_deposit',
        ));
    }
}
