<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Enums\MsoTransactionTag;
use App\Enums\OthersTransactionExcludedAccounts;
use App\Models\Account;
use App\Models\Saving;
use App\Models\Imprest;
use App\Models\LoveGift;
use App\Models\Transaction;
use Filament\Pages\Page;
use App\Models\LoanPayment;
use App\Models\TimeDeposit;
use App\Models\PaymentType;
use Livewire\Attributes\Computed;

class DailyCollectionsReport extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.app.pages.cashier.reports.daily-collections-report';

    #[Computed]
    public function PaymentTypes()
    {
        return PaymentType::whereIn('id', [1, 3, 4, 5])->get();
    }

    #[Computed]
    public function GeneralFundDeposits()
    {
        return Account::getCashInBankGF()
            ->recursiveTransactions()
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->sum('debit');
    }

    #[Computed]
    public function MsoDeposits()
    {
        return Account::getCashInBankMSO()
            ->recursiveTransactions()
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->sum('debit');
    }

    #[Computed]
    public function DailyCollections()
    {
        $dormitory = Transaction::query()->whereIn('account_id', [
            OthersTransactionExcludedAccounts::RESERVATION_FEES_DORM->value,
            OthersTransactionExcludedAccounts::DORMITORY->value,
            OthersTransactionExcludedAccounts::RESERVATION->value,
            OthersTransactionExcludedAccounts::OTHER_INCOME_ELECTRICITY->value,
            OthersTransactionExcludedAccounts::OTHER_INCOME_ELECTRICITY->value,
        ])
            ->selectRaw(
                "sum(credit) as total_amount, 'Dormitory' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");
        $rice_and_groceries = Transaction::query()->whereIn('account_id', [
            OthersTransactionExcludedAccounts::RICE->value,
            OthersTransactionExcludedAccounts::GROCERIES->value
        ])->where('transaction_type_id', 1)
            ->selectRaw(
                "sum(credit) as total_amount, 'Rice and Groceries' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");


        $savings = Saving::query()
            ->withoutGlobalScopes()
            ->selectRaw(
                "sum(deposit) as total_amount, 'Savings' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        $imprests = Imprest::query()
            ->withoutGlobalScopes()
            ->selectRaw(
                "sum(deposit) as total_amount, 'Imprests' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        $love_gifts = LoveGift::query()
            ->withoutGlobalScopes()
            ->selectRaw(
                "sum(deposit) as total_amount, 'Love Gift' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        $time_deposits = TimeDeposit::query()
            ->selectRaw(
                "sum(amount) as total_amount, 'Time Deposit' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        $laboratory = Transaction::query()
            ->where(function ($query) {
                $query->whereIn('account_id', [OthersTransactionExcludedAccounts::MEMBERSHIP_FEES->value])
                    ->orWhere(fn($query) => $query->whereRelation('account', function ($query) {
                        return $query->whereRelation('parent', 'tag', 'member_laboratory_cbu_paid');
                    }));
            })
            ->selectRaw(
                "sum(credit) as total_amount, 'Laboratory' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");
        $loans = LoanPayment::query()->whereIn('payment_type_id', [1, 3, 4, 5])
            ->selectRaw(
                "sum(amount) as total_amount, 'Loans' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");
        $others = Transaction::whereDoesntHave("account", function ($query) {
            return $query->whereHas(
                "rootAncestor",
                fn($q) => $q->whereIn("id", OthersTransactionExcludedAccounts::get())
            );
        })
            ->withoutMso()
            ->selectRaw(
                "sum(credit) as total_amount, 'Others' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        return $dormitory
            ->union($rice_and_groceries)
            ->union($savings)
            ->union($imprests)
            ->union($love_gifts)
            ->union($time_deposits)
            ->union($laboratory)
            ->union($loans)
            ->union($others)
            ->get()
            ->groupBy("payment_type_id");
    }
}
