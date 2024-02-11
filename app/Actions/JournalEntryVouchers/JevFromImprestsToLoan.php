<?php

namespace App\Actions\JournalEntryVouchers;

use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Models\Loan;
use App\Models\Member;
use App\Models\TrialBalanceEntry;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

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
