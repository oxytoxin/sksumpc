<?php

namespace App\Actions\JournalEntryVouchers;

use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Models\LoanType;
use App\Actions\Loans\PayLoan;
use App\Models\TrialBalanceEntry;
use Illuminate\Support\Facades\DB;
use App\Models\JournalEntryVoucher;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Models\JournalEntryVoucherItem;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\Loan;
use App\Models\Member;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherData;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherItemData;
use App\Oxytoxin\DTO\MSO\ImprestData;

class JevFromImprestsToLoan
{
    use AsAction;

    public function handle(JournalEntryVoucherData $journalEntryVoucherData, Member $member, Loan $loan, $amount)
    {
        DB::beginTransaction();
        app(WithdrawFromImprestAccount::class)->handle(
            member: $member,
            data: new ImprestData(
                payment_type_id: 2,
                reference_number: $journalEntryVoucherData->reference_number,
                amount: $amount
            )
        );
        $imprests_trial_balance_entry = TrialBalanceEntry::where('code', '21110')->first();
        app(PayLoanAndCreateJevEntriesFromMso::class)->handle(
            journalEntryVoucherData: $journalEntryVoucherData,
            trialBalanceEntry: $imprests_trial_balance_entry,
            loan: $loan,
            amount: $amount
        );
        DB::commit();
    }
}
