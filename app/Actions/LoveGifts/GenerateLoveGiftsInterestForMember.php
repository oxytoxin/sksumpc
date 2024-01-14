<?php

namespace App\Actions\LoveGifts;

use App\Models\Member;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\LoveGiftProvider;
use App\Oxytoxin\Services\InterestCalculator;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateLoveGiftsInterestForMember
{
    use AsAction;

    public function handle(Member $member)
    {
        $interestCalculator = app(InterestCalculator::class);
        DB::beginTransaction();
        $member->imprests_no_interest()->each(function ($i) use ($interestCalculator) {
            $i->update([
                'interest' => $interestCalculator->calculate(
                    amount: $i->balance,
                    rate: $i->interest_rate,
                    days: $i->days_till_next_transaction,
                    minimum_amount: LoveGiftProvider::MINIMUM_AMOUNT_FOR_INTEREST
                ),
                'interest_date' => today(),
            ]);
        });

        $total_interest = $member->imprests_unaccrued()->sum('interest');
        app(DepositToLoveGiftsAccount::class)->handle($member, new LoveGiftData(
            payment_type_id: 1,
            reference_number: '#INTEREST',
            amount: $total_interest
        ));
        $member->imprests_unaccrued()->update([
            'accrued' => true,
        ]);
        DB::commit();
    }
}
