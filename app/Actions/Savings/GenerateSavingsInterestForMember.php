<?php

namespace App\Actions\Savings;

use App\Models\Member;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\SavingsProvider;
use App\Oxytoxin\Services\InterestCalculator;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateSavingsInterestForMember
{
    use AsAction;

    public function handle(Member $member)
    {
        $interestCalculator = app(InterestCalculator::class);
        DB::beginTransaction();
        foreach ($member->savings_accounts as $account) {
            $account->savings_no_interest()->each(function ($s) use ($interestCalculator) {
                $s->update([
                    'interest' => $interestCalculator->calculate(
                        amount: $s->balance,
                        rate: $s->interest_rate,
                        days: $s->days_till_next_transaction,
                        minimum_amount: SavingsProvider::MINIMUM_AMOUNT_FOR_INTEREST
                    ),
                    'interest_date' => today(),
                ]);
            });

            $total_interest = $account->savings_unaccrued()->sum('interest');
            app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                payment_type_id: 1,
                reference_number: '#INTEREST',
                amount: $total_interest,
                savings_account_id: $account->id,
            ));
            $account->savings_unaccrued()->update([
                'accrued' => true,
            ]);
        }
        DB::commit();
    }
}
