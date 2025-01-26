<?php

namespace App\Observers;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\Loans\PayLegacyLoan;
use App\Actions\Loans\PayLoan;
use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoType;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\JournalEntryVoucherItem;
use App\Models\LoanAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class JournalEntryVoucherItemObserver
{
    private function getCbuAmount(JournalEntryVoucherItem $journalEntryVoucherItem)
    {
        if ($journalEntryVoucherItem->credit > 0) {
            return $journalEntryVoucherItem->credit;
        }

        return $journalEntryVoucherItem->debit * -1;
    }

    private function processCbu() {}

    public function creating(JournalEntryVoucherItem $journalEntryVoucherItem): void
    {
        $account = Account::find($journalEntryVoucherItem->account_id);
        $transaction_date = (config('app.transaction_date') ?? today());
        $transactionType = TransactionType::JEV();
        $transaction_data = new TransactionData(
            member_id: $account->member_id,
            account_id: $account->id,
            transactionType: $transactionType,
            payment_type_id: PaymentTypes::JEV->value,
            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
            debit: $journalEntryVoucherItem->debit,
            credit: $journalEntryVoucherItem->credit,
            transaction_date: $transaction_date,
            payee: $account->member?->full_name ?? 'SKSU-MPC',
        );
        if (in_array($account->tag, ['member_regular_cbu_paid', 'member_preferred_cbu_paid', 'member_laboratory_cbu_paid'])) {
            $amount = self::getCbuAmount($journalEntryVoucherItem);
            app(PayCapitalSubscription::class)->handle($account->member->active_capital_subscription, $transaction_data);
            if ($amount < 0) {
                $account->member->active_capital_subscription->update([
                    'is_active' => false,
                ]);
            }
        } elseif (in_array($account->tag, ['regular_savings'])) {
            if ($journalEntryVoucherItem->credit) {
                app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $transaction_data);
            }
            if ($journalEntryVoucherItem->debit) {
                app(WithdrawFromMsoAccount::class)->handle(MsoType::SAVINGS, $transaction_data);
            }
        } elseif (in_array($account->tag, ['imprest_savings'])) {
            if ($journalEntryVoucherItem->credit) {
                app(DepositToMsoAccount::class)->handle(MsoType::IMPREST, $transaction_data);
            }
            if ($journalEntryVoucherItem->debit) {
                app(WithdrawFromMsoAccount::class)->handle(MsoType::IMPREST, $transaction_data);
            }
        } elseif (in_array($account->tag, ['love_gift_savings'])) {
            if ($journalEntryVoucherItem->credit) {
                app(DepositToMsoAccount::class)->handle(MsoType::LOVE_GIFT, $transaction_data);
            }
            if ($journalEntryVoucherItem->debit) {
                app(WithdrawFromMsoAccount::class)->handle(MsoType::LOVE_GIFT, $transaction_data);
            }
        } elseif (in_array($account->tag, ['member_loans_receivable'])) {
            $loan_account = LoanAccount::find($account->id);
            if ($journalEntryVoucherItem->credit) {
                if ($journalEntryVoucherItem->journal_entry_voucher->is_legacy) {
                    app(PayLegacyLoan::class)
                        ->handle(
                            loanAccount: $loan_account,
                            principal: $journalEntryVoucherItem->details['principal'],
                            interest: $journalEntryVoucherItem->details['interest'],
                            payment_type_id: PaymentTypes::JEV->value,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            transaction_date: $transaction_date,
                            transactionType: $transactionType,
                        );
                } else {
                    app(PayLoan::class)->handle(
                        loan: $loan_account->loan,
                        loanPaymentData: new LoanPaymentData(
                            payment_type_id: PaymentTypes::JEV->value,
                            reference_number: $journalEntryVoucherItem->journal_entry_voucher->reference_number,
                            amount: $journalEntryVoucherItem->credit,
                            transaction_date: $transaction_date
                        ),
                        transactionType: $transactionType,
                    );
                }
            }
        } else {
            app(CreateTransaction::class)->handle($transaction_data);
        }
    }
}
