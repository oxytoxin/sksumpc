<?php

namespace App\Actions\Savings;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoType;
use App\Models\Account;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Services\InterestCalculator;
use DB;

class GenerateSavingsInterestForMember
{
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
            app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, new TransactionData(
                account_id: $account->id,
                transactionType: TransactionType::CDJ(),
                payment_type_id: 1,
                reference_number: '#INTERESTACCRUED-'.$account->number,
                credit: $total_interest,
                member_id: $member->id,
                remarks: 'Savings Interest',
                payee: $member->full_name,
                transaction_date: today(),
            ));
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getSavingsInterestExpense()->id,
                transactionType: TransactionType::CDJ(),
                payment_type_id: 1,
                reference_number: '#INTERESTACCRUED-'.$account->number,
                debit: $total_interest,
                member_id: $member->id,
                remarks: 'Savings Interest',
                transaction_date: today(),
            ));
            $account->savings_unaccrued()->update([
                'accrued' => true,
            ]);
        }
        DB::commit();
    }
}
