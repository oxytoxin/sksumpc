<?php

namespace App\Actions\JournalEntryVouchers;

use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\TrialBalanceEntry;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class JevFromSavingsToLoan
{
    use AsAction;

    public function handle(JournalEntryVoucherData $journalEntryVoucherData, SavingsAccount $savingsAccount, Loan $loan, $amount)
    {
        DB::beginTransaction();
        app(WithdrawFromSavingsAccount::class)->handle(
            member: $savingsAccount->member,
            data: new SavingsData(
                payment_type_id: 2,
                reference_number: $journalEntryVoucherData->reference_number,
                amount: $amount,
                savings_account_id: $savingsAccount->id,
            )
        );

        $savings_trial_balance_entry = TrialBalanceEntry::where('code', '21110')->first();
        app(PayLoanAndCreateJevEntriesFromMso::class)->handle(
            journalEntryVoucherData: $journalEntryVoucherData,
            trialBalanceEntry: $savings_trial_balance_entry,
            loan: $loan,
            amount: $amount
        );
        DB::commit();
    }
}
