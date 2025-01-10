<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\TimeDepositsProvider;

class CashierTransactionsPageTimeDeposit
{
    public function handle(TransactionData $data)
    {
        $member = Member::find($data->member_id);
        $td = app(CreateTimeDeposit::class)->handle(timeDepositData: new TimeDepositData(
            member_id: $data->member_id,
            maturity_date: TimeDepositsProvider::getMaturityDate($data->transaction_date),
            reference_number: $data->reference_number,
            payment_type_id: $data->payment_type_id,
            amount: $data->credit,
            maturity_amount: TimeDepositsProvider::getMaturityAmount(floatval($data->credit)),
            transaction_date: $data->transaction_date,
        ), transactionType: TransactionType::CRJ());

        $data->debit = $data->credit;
        $data->credit = null;
        $data->account_id = Account::getCashOnHand()->id;
        app(CreateTransaction::class)->handle($data);

        $time_deposit_account = $td->time_deposit_account;

        return [
            'account_number' => $time_deposit_account->number,
            'account_name' => $time_deposit_account->name,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit,
            'payment_type' => PaymentType::find($data->payment_type_id)->name,
            'payee' => $member->full_name,
            'remarks' => 'TIME DEPOSIT',
        ];
    }
}
