<?php

    use App\Actions\MSO\DepositToMsoAccount;
    use App\Actions\Transactions\CreateTransaction;
    use App\Enums\MsoType;
    use App\Models\Account;
    use App\Models\LoveGift;
    use App\Models\Member;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use App\Oxytoxin\Providers\LoveGiftProvider;
    use App\Oxytoxin\Services\InterestCalculator;
    use Illuminate\Database\Migrations\Migration;

    return new class extends Migration {
        public function up(): void
        {
            $members = Member::all();
            $dates = ['2025-06-30', '2025-09-30'];
            DB::beginTransaction();
            foreach ($dates as $date) {
                foreach ($members as $member) {
                    $interestCalculator = app(InterestCalculator::class);
                    $balance = 0;
                    $account = $member->love_gift_account;
                    foreach ($member->love_gifts()->orderBy("transaction_date")->get() as $s) {
                        $balance += $s->amount;
                        $s->update([
                            "balance" => $balance
                        ]);
                    }
                    $love_gifts_without_interest = LoveGift::whereNull("interest_date")
                        ->where("member_id", $member->id)
                        ->get();

                    foreach ($love_gifts_without_interest as $key => $love_gift) {
                        $next_love_gift = $love_gifts_without_interest[$key + 1] ?? null;

                        if (!$next_love_gift || $next_love_gift->transaction_date?->gt($date)) {
                            $days_till_next_transaction = $love_gift->transaction_date->diffInDays($date);
                        } else {
                            $days_till_next_transaction = $love_gift->transaction_date->diffInDays($next_love_gift->transaction_date);
                        }
                        if ($love_gift->transaction_date->gt($date)) {
                            break;
                        }

                        $love_gift->update([
                            "interest" => $interestCalculator->calculate(
                                amount: $love_gift->balance,
                                rate: $love_gift->interest_rate,
                                days: $days_till_next_transaction,
                                minimum_amount: LoveGiftProvider::MINIMUM_AMOUNT_FOR_INTEREST
                            ),
                            "interest_date" => $date
                        ]);
                    }

                    $total_interest = LoveGift::whereNotNull("interest_date")
                        ->where("accrued", false)
                        ->where("member_id", $member->id)
                        ->sum("interest");
                    $total_interest = round($total_interest, 2);
                    LoveGift::whereNotNull("interest_date")
                        ->where("accrued", false)
                        ->where("member_id", $member->id)
                        ->update([
                            "accrued" => true
                        ]);
                    if ($total_interest > 0) {
                        $account->refresh();
                        app(DepositToMsoAccount::class)->handle(
                            MsoType::LOVE_GIFT,
                            new TransactionData(
                                account_id: $account->id,
                                transactionType: TransactionType::CDJ(),
                                reference_number: "#INTERESTACCRUED-".$account->number,
                                payment_type_id: 1,
                                credit: $total_interest,
                                member_id: $member->id,
                                remarks: "Love Gift Interest",
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
                                remarks: "Love Gift Interest",
                                transaction_date: $date
                            )
                        );
                    }
                    $balance = 0;
                    foreach ($member->love_gifts()->orderBy("transaction_date")->get() as $s) {
                        $balance += $s->amount;
                        $s->update([
                            "balance" => $balance
                        ]);
                    }
                }
            }
            DB::commit();

        }
    };
