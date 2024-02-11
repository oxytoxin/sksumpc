<?php

namespace App\Actions\JournalEntryVouchers;

use App\Actions\Loans\PayLoan;
use App\Models\JournalEntryVoucher;
use App\Models\JournalEntryVoucherItem;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\TrialBalanceEntry;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherData;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherItemData;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use Lorisleiva\Actions\Concerns\AsAction;

class PayLoanAndCreateJevEntriesFromMso
{
    use AsAction;

    public function handle(JournalEntryVoucherData $journalEntryVoucherData, TrialBalanceEntry $trialBalanceEntry, Loan $loan, $amount)
    {
        $loan_payment = app(PayLoan::class)->handle(
            loan: $loan,
            loanPaymentData: new LoanPaymentData(
                payment_type_id: 2,
                reference_number: $journalEntryVoucherData->reference_number,
                amount: $amount,
            )
        );
        $jev = JournalEntryVoucher::create($journalEntryVoucherData->toArray());
        $loan_principal_trial_balance_entry = TrialBalanceEntry::whereAuditableType(LoanType::class)->whereAuditableId($loan->loan_type_id)->whereCode('11210')->first();
        $loan_interest_trial_balance_entry = TrialBalanceEntry::whereAuditableType(LoanType::class)->whereAuditableId($loan->loan_type_id)->whereCode('40110')->first();
        $surcharge_trial_balance_entry = TrialBalanceEntry::whereCode('40140')->first();
        JournalEntryVoucherItem::create((new JournalEntryVoucherItemData(
            journal_entry_voucher_id: $jev->id,
            trial_balance_entry_id: $trialBalanceEntry->id,
            debit: $amount
        ))->toArray());
        if ($loan_payment->principal_payment > 0) {
            JournalEntryVoucherItem::create((new JournalEntryVoucherItemData(
                journal_entry_voucher_id: $jev->id,
                trial_balance_entry_id: $loan_principal_trial_balance_entry->id,
                credit: $loan_payment->principal_payment
            ))->toArray());
        }
        if ($loan_payment->interest_payment > 0) {
            JournalEntryVoucherItem::create((new JournalEntryVoucherItemData(
                journal_entry_voucher_id: $jev->id,
                trial_balance_entry_id: $loan_interest_trial_balance_entry->id,
                credit: $loan_payment->interest_payment
            ))->toArray());
        }
        if ($loan_payment->surcharge_payment > 0) {
            JournalEntryVoucherItem::create((new JournalEntryVoucherItemData(
                journal_entry_voucher_id: $jev->id,
                trial_balance_entry_id: $surcharge_trial_balance_entry->id,
                credit: $loan_payment->surcharge_payment
            ))->toArray());
        }
    }
}
