<?php

    use App\Actions\MSO\DepositToMsoAccount;
    use App\Actions\Transactions\CreateTransaction;
    use App\Enums\MsoType;
    use App\Models\Account;
    use App\Models\Member;
    use App\Models\Saving;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use App\Oxytoxin\Providers\SavingsProvider;
    use App\Oxytoxin\Services\InterestCalculator;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            $members = Member::all();
            $dates = ['2025-06-30', '2025-09-30'];
            DB::beginTransaction();
            foreach ($dates as $date) {
                foreach ($members as $member) {
                    $interestCalculator = app(InterestCalculator::class);
                    foreach ($member->savings_accounts as $account) {
                        $balance = 0;
                        foreach ($account->savings()->orderBy("transaction_date")->get() as $s) {
                            $balance += $s->amount;
                            $s->update([
                                "balance" => $balance
                            ]);
                        }
                        $savings_without_interest = Saving::whereNull("interest_date")
                            ->where("savings_account_id", $account->id)
                            ->get();

                        foreach ($savings_without_interest as $key => $savings) {
                            $next_savings = $savings_without_interest[$key + 1] ?? null;

                            if (!$next_savings || $next_savings->transaction_date?->gt($date)) {
                                $days_till_next_transaction = $savings->transaction_date->diffInDays($date);
                            } else {
                                $days_till_next_transaction = $savings->transaction_date->diffInDays($next_savings->transaction_date);
                            }
                            if ($savings->transaction_date->gt($date)) {
                                break;
                            }

                            $savings->update([
                                "interest" => $interestCalculator->calculate(
                                    amount: $savings->balance,
                                    rate: $savings->interest_rate,
                                    days: $days_till_next_transaction,
                                    minimum_amount: SavingsProvider::MINIMUM_AMOUNT_FOR_INTEREST
                                ),
                                "interest_date" => $date
                            ]);
                        }

                        $total_interest = Saving::whereNotNull("interest_date")
                            ->where("accrued", false)
                            ->where("savings_account_id", $account->id)
                            ->sum("interest");
                        $total_interest = round($total_interest, 2);
                        Saving::whereNotNull("interest_date")
                            ->where("accrued", false)
                            ->where("savings_account_id", $account->id)
                            ->update([
                                "accrued" => true
                            ]);
                        if ($total_interest > 0) {
                            $account->refresh();
                            app(DepositToMsoAccount::class)->handle(
                                MsoType::SAVINGS,
                                new TransactionData(
                                    account_id: $account->id,
                                    transactionType: TransactionType::CDJ(),
                                    reference_number: "#INTERESTACCRUED-".$account->number,
                                    payment_type_id: 1,
                                    credit: $total_interest,
                                    member_id: $member->id,
                                    remarks: "Savings Interest",
                                    transaction_date: $date,
                                    payee: $member->full_name
                                )
                            );
                            app(CreateTransaction::class)->handle(
                                new TransactionData(
                                    account_id: Account::getSavingsInterestExpense()->id,
                                    transactionType: TransactionType::CDJ(),
                                    reference_number: "#INTERESTACCRUED-".$account->number,
                                    payment_type_id: 1,
                                    debit: $total_interest,
                                    member_id: $member->id,
                                    remarks: "Savings Interest",
                                    transaction_date: $date
                                )
                            );
                        }
                        $balance = 0;
                        foreach ($account->savings()->orderBy("transaction_date")->get() as $s) {
                            $balance += $s->amount;
                            $s->update([
                                "balance" => $balance
                            ]);
                        }
                    }
                }
            }
            DB::commit();

        }
    };
