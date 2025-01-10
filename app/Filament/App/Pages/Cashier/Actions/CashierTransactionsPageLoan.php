<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\Loans\PayLoan;
use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\LoanAccount;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CashierTransactionsPageLoan
{
    public function handle(TransactionData $data)
    {
        $loan_account = LoanAccount::find($data->account_id);
        $loan = $loan_account?->loan;
        $member = Member::find($data->member_id);

        app(PayLoan::class)->handle($loan, new LoanPaymentData(
            payment_type_id: $data->payment_type_id,
            reference_number: $data->reference_number,
            amount: $data->credit,
            remarks: $data->remarks,
            transaction_date: $data->transaction_date,
        ), TransactionType::CRJ());

        $data->debit = $data->credit;
        $data->credit = null;

        $data->account_id = Account::getCashOnHand()->id;

        app(CreateTransaction::class)->handle($data);

        return [
            'account_number' => $loan_account->number,
            'account_name' => $loan_account->name,
            'reference_number' => $data->account_id,
            'amount' => $data->debit,
            'payment_type' => PaymentType::find($data->payment_type_id)->name,
            'remarks' => $data->remarks ?? 'LOAN PAYMENT',
            'payee' => $member->full_name,
        ];
    }
}
