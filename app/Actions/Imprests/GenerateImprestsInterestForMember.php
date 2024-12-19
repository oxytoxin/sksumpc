<?php

namespace App\Actions\Imprests;

use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Services\InterestCalculator;
use DB;


class GenerateImprestsInterestForMember
{


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
                    minimum_amount: ImprestsProvider::MINIMUM_AMOUNT_FOR_INTEREST
                ),
                'interest_date' => today(),
            ]);
        });

        $total_interest = $member->imprests_unaccrued()->sum('interest');
        app(DepositToImprestAccount::class)->handle($member, new ImprestData(
            payment_type_id: 1,
            reference_number: '#INTEREST',
            amount: $total_interest
        ), TransactionType::firstWhere('name', 'CDJ'));
        $member->imprests_unaccrued()->update([
            'accrued' => true,
        ]);
        DB::commit();
    }
}
