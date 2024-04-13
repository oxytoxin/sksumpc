<?php

namespace App\Actions\TimeDeposits;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\TimeDeposit;
use App\Models\TimeDepositAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTimeDeposit
{
    use AsAction;

    public function handle(TimeDepositData $timeDepositData, TransactionType $transactionType, $account_number = null)
    {
        DB::beginTransaction();
        $account_number ??= str('21112-1015-')
            ->append(str_pad((TimeDepositAccount::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
        $member = Member::find($timeDepositData->member_id);
        $member_time_deposits = Account::getMemberTimeDeposits();
        $tda = Account::create([
            'name' => strtoupper($member->full_name),
            'number' => $account_number,
            'account_type_id' => $member_time_deposits->account_type_id,
            'member_id' => $member->id,
            'tag' => 'member_time_deposits',
        ], $member_time_deposits);
        $td = TimeDeposit::create([
            'member_id' => $timeDepositData->member_id,
            'maturity_date' => $timeDepositData->maturity_date,
            'reference_number' => $timeDepositData->reference_number,
            'payment_type_id' => $timeDepositData->payment_type_id,
            'amount' => $timeDepositData->amount,
            'maturity_amount' => $timeDepositData->maturity_amount,
            'transaction_date' => $timeDepositData->transaction_date,
            'time_deposit_account_id' => $tda->id,
        ]);
        if ($timeDepositData->payment_type_id == 1) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashOnHand()->id,
                transactionType: $transactionType,
                reference_number: $td->reference_number,
                debit: $td->amount,
                member_id: $td->member_id,
                remarks: 'Member Time Deposit',
                transaction_date: $timeDepositData->transaction_date,
            ));
        }
        if ($timeDepositData->payment_type_id == 4) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashInBankMSO()->id,
                transactionType: $transactionType,
                reference_number: $td->reference_number,
                debit: $td->amount,
                member_id: $td->member_id,
                remarks: 'Member Time Deposit',
                transaction_date: $timeDepositData->transaction_date,
            ));
        }
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $tda->id,
            transactionType: $transactionType,
            reference_number: $td->reference_number,
            credit: $td->amount,
            member_id: $td->member_id,
            remarks: 'Member Time Deposit',
            tag: 'member_time_deposit',
            transaction_date: $timeDepositData->transaction_date,
        ));
        DB::commit();

        return $td;
    }
}
