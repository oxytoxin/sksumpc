<?php

namespace App\Actions\Imprests;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoType;
use App\Models\Account;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
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
        $member->imprests_unaccrued()->update([
            'accrued' => true,
        ]);
        if ($total_interest > 0) {
            app(DepositToMsoAccount::class)->handle(MsoType::IMPREST, new TransactionData(
                account_id: $member->imprest_account->id,
                transactionType: TransactionType::CDJ(),
                payment_type_id: 1,
                reference_number: '#INTERESTACCRUED-' . $member->imprest_account->number,
                credit: $total_interest,
                member_id: $member->id,
                remarks: 'Imprest Interest',
                payee: $member->full_name,
                transaction_date: today(),
            ));
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getSavingsInterestExpense()->id,
                transactionType: TransactionType::CDJ(),
                payment_type_id: 1,
                reference_number: '#INTERESTACCRUED-' . $member->imprest_account->number,
                debit: $total_interest,
                member_id: $member->id,
                remarks: 'Imprest Interest',
                transaction_date: today(),
            ));
        }


        DB::commit();
    }
}
