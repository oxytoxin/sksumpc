<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Models\Member;
use App\Models\Account;
use App\Enums\PaymentTypes;
use App\Models\LoanAccount;
use App\Models\PaymentType;
use App\Actions\Loans\PayLoan;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Actions\Transactions\CreateTransaction;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class CashierTransactionsPageLoan
{
    public function handle(TransactionData $data)
    {
        $loan_account = LoanAccount::find($data->account_id);
        $loan = $loan_account?->loan;
        $member = Member::find($data->member_id);
        if ($loan->outstanding_balance < $data->credit) {
            Notification::make()->title('Loan Overpayment')->body('Payment amount exceeds outstanding balance.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Loan Overpayment. Payment amount exceeds outstanding balance.',
            ]);
        }
        app(PayLoan::class)->handle($loan, new LoanPaymentData(
            payment_type_id: $data->payment_type_id,
            reference_number: $data->reference_number,
            amount: $data->credit,
            remarks: $data->remarks,
            transaction_date: $data->transaction_date,
        ), TransactionType::CRJ());

        $data->debit = $data->credit;
        $data->credit = null;
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        if ($data->payment_type_id == PaymentTypes::ADA->value) {
            $data->account_id = $cash_in_bank_account_id;
        } else {
            $data->account_id = $cash_on_hand_account_id;
        }
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
