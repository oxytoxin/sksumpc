<?php

namespace App\Actions\Savings;

use App\Models\Member;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\SavingsProvider;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateSavingsInterestForMember
{
    use AsAction;

    public function handle(Member $member)
    {
        DB::beginTransaction();
        foreach ($member->savings_accounts as $account) {
            $account->savings_no_interest()->each(function ($s) {
                if ($s->days_till_next_transaction >= 0) {
                    $s->update([
                        'interest' => $this->calculateInterest($s->balance, $s->interest_rate, $s->days_till_next_transaction),
                        'interest_date' => today()
                    ]);
                }
            });

            $total_interest = $account->savings_unaccrued()->sum('interest');
            DepositToSavingsAccount::run($member, new SavingsData(1, '#INTEREST', $total_interest, $account->id));
            $account->savings_unaccrued()->update([
                'accrued' => true
            ]);
        }
        DB::commit();
    }

    protected function calculateInterest($amount, $interest_rate, $days)
    {
        if ($amount < SavingsProvider::MINIMUM_AMOUNT_FOR_INTEREST) {
            return 0;
        }

        return $amount * $interest_rate * $days / 365;
    }
}
