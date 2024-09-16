<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Account;
use App\Models\Saving;
use App\Models\Imprest;
use App\Models\LoveGift;
use App\Models\Transaction;
use Filament\Pages\Page;
use App\Models\LoanPayment;
use App\Models\TimeDeposit;
use App\Models\CashCollectiblePayment;
use App\Models\CapitalSubscriptionPayment;
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
        $dormitory = Transaction::query()->whereIn('account_id', [80, 94, 157])
            ->selectRaw(
                "sum(credit) as total_amount, 'Dormitory' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");
        $rice_and_groceries = Transaction::query()->whereIn('account_id', [151])->where('transaction_type_id', 1)
            ->selectRaw(
                "sum(credit) as total_amount, 'Rice and Groceries' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        $laboratory = Transaction::query()
            ->where(function ($query) {
                $query->whereIn('account_id', [81])
                    ->orWhere(fn($query) => $query->whereRelation('account', function ($query) {
                        return $query->whereRelation('parent', 'tag', 'member_laboratory_cbu_paid');
                    }));
            })
            ->selectRaw(
                "sum(credit) as total_amount, 'Laboratory' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");
        $mso = Transaction::whereIn("tag", [
            "member_savings_deposit",
            "member_savings_withdrawal",
            "member_imprest_deposit",
            "member_imprest_withdrawal",
            "member_love_gift_deposit",
            "member_love_gift_withdrawal",
            "member_time_deposit"
        ])
            ->selectRaw(
                "sum(credit) as total_amount, 'MSO' as name, payment_type_id"
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
                fn($q) => $q->whereIn("id", [14, 75, 151, 80, 94, 157, 81, 101, 105])
            );
        })
            ->whereNotIn("tag", [
                "member_savings_deposit",
                "member_savings_withdrawal",
                "member_imprest_deposit",
                "member_imprest_withdrawal",
                "member_love_gift_deposit",
                "member_love_gift_withdrawal",
                "member_time_deposit"
            ])
            ->selectRaw(
                "sum(credit) as total_amount, 'Others' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        return $dormitory
            ->union($rice_and_groceries)
            ->union($laboratory)
            ->union($mso)
            ->union($loans)
            ->union($others)
            ->get()
            ->groupBy("payment_type_id");
    }
}
