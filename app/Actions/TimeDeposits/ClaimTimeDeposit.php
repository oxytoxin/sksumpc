<?php

namespace App\Actions\TimeDeposits;

use App\Models\Account;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Actions\Transactions\CreateTransaction;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class ClaimTimeDeposit
{
    public function handle(TimeDeposit $timeDeposit, $withdrawal_date)
    {
        $timeDeposit->update([
            'withdrawal_date' => $withdrawal_date,
        ]);

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: Account::getCashInBankMSO()->id,
            transactionType: TransactionType::firstWhere('name', 'CRJ'),
            reference_number: $timeDeposit->reference_number,
            credit: $timeDeposit->maturity_amount,
            member_id: $timeDeposit->member_id,
            remarks: 'Member Time Deposit Claim'
        ));

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $timeDeposit->time_deposit_account_id,
            transactionType: TransactionType::firstWhere('name', 'CRJ'),
            reference_number: $timeDeposit->reference_number,
            debit: $timeDeposit->maturity_amount,
            member_id: $timeDeposit->member_id,
            remarks: 'Member Time Deposit Claim',
            tag: 'member_time_deposit',
        ));
    }
}