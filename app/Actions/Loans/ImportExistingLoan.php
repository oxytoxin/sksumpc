<?php

namespace App\Actions\Loans;

use App\Actions\LoanApplications\CreateNewLoanApplication;
use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\LoanApplication;
use App\Models\LoanType;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanApplicationData;
use App\Oxytoxin\DTO\Loan\LoanData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\LoansProvider;
use Illuminate\Support\Facades\DB;

class ImportExistingLoan
{
    public function handle(Member $member, LoanType $loanType, $reference_number, $amount, $balance_forwarded, $number_of_terms, $application_date, $last_transaction_date)
    {
        DB::beginTransaction();
        $loan_application = app(CreateNewLoanApplication::class)->handle(new LoanApplicationData(
            member_id: $member->id,
            loan_type_id: $loanType->id,
            number_of_terms: $number_of_terms,
            priority_number: $reference_number,
            reference_number: $reference_number,
            desired_amount: $amount,
            monthly_payment: LoansProvider::computeMonthlyPayment(
                amount: $amount,
                loanType: $loanType,
                number_of_terms: $number_of_terms,
            ),
            transaction_date: $application_date
        ));
        $loan_application->update([
            'status' => LoanApplication::STATUS_APPROVED,
            'approval_date' => date_create($application_date),
        ]);

        $loan_disclosure_sheet_items = [];
        $loan_disclosure_sheet_items[] = [
            'member_id' => null,
            'account_id' => Account::getLoanReceivable($loanType)->id,
            'credit' => null,
            'debit' => $amount,
            'readonly' => true,
            'code' => 'gross_amount',
        ];
        $loan_disclosure_sheet_items[] = [
            'member_id' => null,
            'account_id' => Account::getCashInBankGF()->id,
            'credit' => $amount,
            'debit' => null,
            'readonly' => true,
            'code' => 'net_amount',
        ];

        $accounts = Account::withCode()->find(collect($loan_disclosure_sheet_items)->pluck('account_id'));
        $items = collect($loan_disclosure_sheet_items)->map(function ($item) use ($accounts) {
            $item['name'] = $accounts->find($item['account_id'])->code;
            return $item;
        })->toArray();
        $loanData = new LoanData(
            member_id: $member->id,
            loan_application_id: $loan_application->id,
            loan_type_id: $loanType->id,
            reference_number: $loan_application->reference_number,
            interest_rate: $loanType->interest_rate,
            disclosure_sheet_items: $items,
            priority_number: $loan_application->priority_number,
            gross_amount: $loan_application->desired_amount,
            number_of_terms: $loan_application->number_of_terms,
            interest: LoansProvider::computeInterest(
                amount: $loan_application->desired_amount,
                loanType: $loanType,
                number_of_terms: $loan_application->number_of_terms,
            ),
            monthly_payment: LoansProvider::computeMonthlyPayment(
                amount: $loan_application->desired_amount,
                loanType: $loanType,
                number_of_terms: $loan_application->number_of_terms,
            ),
            release_date: $application_date,
            transaction_date: $application_date,
        );
        $loan = app(CreateNewLoan::class)->handle($loan_application, $loanData);
        $loan->posted = true;
        $loan->save();

        $principal_payment = $loan->gross_amount - $balance_forwarded;
        $transactionType = TransactionType::JEV();
        if ($principal_payment)
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $loan->loan_account->id,
                transactionType: $transactionType,
                payment_type_id: 2,
                reference_number: '#BALANCEFORWARDED',
                credit: $principal_payment,
                member_id: $loan->member_id,
                remarks: 'Member Loan Receivable Before Balance Forwarded',
                transaction_date: $last_transaction_date,
            ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $loan->loan_account->id,
            transactionType: $transactionType,
            payment_type_id: 2,
            reference_number: '#BALANCEFORWARDED',
            debit: $loan->gross_amount,
            member_id: $loan->member_id,
            remarks: 'Member Loan Balance Forwarded',
            transaction_date: $last_transaction_date,
        ));
        $loan->payments()->create([
            'member_id' => $loan->member_id,
            'payment_type_id' => 2,
            'amount' => $principal_payment,
            'interest_payment' => 0,
            'principal_payment' => $principal_payment,
            'reference_number' => '#BALANCEFORWARDED',
            'remarks' => 'Member Loan Payment Before Balance Forwarded',
            'transaction_date' => $last_transaction_date,
        ]);
        DB::commit();
    }
}
