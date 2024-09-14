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
        return PaymentType::whereIn('id', [1, 3, 4])->get();
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
        $share_capital = CapitalSubscriptionPayment::query()
            ->selectRaw(
                "sum(amount) as total_amount, 'Share Capital' as name, payment_type_id"
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

        $loans = LoanPayment::query()
            ->selectRaw("sum(amount) as total_amount, 'Loans' as name, payment_type_id")
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        $cash_collectibles = Transaction::query()
            ->whereRelation('account', fn($query) => $query->whereIn('parent_id', [16, 18, 91]))
            ->selectRaw(
                "sum(credit) as total_amount, 'Others' as name, payment_type_id"
            )
            ->whereDate('transaction_date', config('app.transaction_date'))
            ->groupBy("payment_type_id");

        return $savings
            ->union($share_capital)
            ->union($imprests)
            ->union($love_gifts)
            ->union($time_deposits)
            ->union($loans)
            ->union($cash_collectibles)
            ->get()
            ->groupBy("payment_type_id");
    }
}
