<?php

namespace App\Actions\LoveGifts;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoType;
use App\Models\Account;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Services\InterestCalculator;
use DB;

class GenerateLoveGiftsInterestForMember
{
    public function handle(Member $member)
    {
        $interestCalculator = app(InterestCalculator::class);
        DB::beginTransaction();
        $member->love_gifts_no_interest()->each(function ($i) use ($interestCalculator) {
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

        $total_interest = $member->love_gifts_unaccrued()->sum('interest');
        $member->love_gifts_unaccrued()->update([
            'accrued' => true,
        ]);
        if ($total_interest > 0) {
            app(DepositToMsoAccount::class)->handle(MsoType::LOVE_GIFT, new TransactionData(
                account_id: $member->imprest_account->id,
                transactionType: TransactionType::CDJ(),
                payment_type_id: 1,
                reference_number: '#INTERESTACCRUED-' . $member->love_gift_account->number,
                credit: $total_interest,
                member_id: $member->id,
                remarks: 'Love Gift Interest',
                payee: $member->full_name,
                transaction_date: today(),
            ));
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getSavingsInterestExpense()->id,
                transactionType: TransactionType::CDJ(),
                payment_type_id: 1,
                reference_number: '#INTERESTACCRUED-' . $member->love_gift_account->number,
                debit: $total_interest,
                member_id: $member->id,
                remarks: 'Love Gift Interest',
                transaction_date: today(),
            ));
        }
        DB::commit();
    }
}
