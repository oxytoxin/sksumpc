<?php

    namespace App\Actions\Imprests;

    use App\Actions\MSO\DepositToMsoAccount;
    use App\Actions\Transactions\CreateTransaction;
    use App\Enums\MsoType;
    use App\Models\Account;
    use App\Models\Imprest;
    use App\Models\Member;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use App\Oxytoxin\Providers\ImprestsProvider;
    use App\Oxytoxin\Services\InterestCalculator;
    use DB;

    class GenerateImprestsInterestForMember
    {
        public function handle(Member $member, $date = null)
        {
            $date = $date ?? today();
            $interestCalculator = app(InterestCalculator::class);
            DB::beginTransaction();
            $member->imprests_no_interest()->each(function (Imprest $i) use ($interestCalculator, $date) {
                $i->update([
                    'interest' => $interestCalculator->calculate(
                        amount: $i->balance,
                        rate: $i->interest_rate,
                        days: $i->days_till_next_transaction,
                        minimum_amount: ImprestsProvider::MINIMUM_AMOUNT_FOR_INTEREST
                    ),
                    'interest_date' => $date,
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
                    reference_number: '#INTERESTACCRUED-'.$member->imprest_account->number,
                    payment_type_id: 1,
                    credit: $total_interest,
                    member_id: $member->id,
                    remarks: 'Imprest Interest',
                    transaction_date: $date,
                    payee: $member->full_name,
                ));
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: Account::getSavingsInterestExpense()->id,
                    transactionType: TransactionType::CDJ(),
                    reference_number: '#INTERESTACCRUED-'.$member->imprest_account->number,
                    payment_type_id: 1,
                    debit: $total_interest,
                    member_id: $member->id,
                    remarks: 'Imprest Interest',
                    transaction_date: $date,
                ));
            }

            DB::commit();
        }
    }
