<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\CashCollectiblePayment;
use App\Models\Imprest;
use App\Models\Saving;
use App\Models\TimeDeposit;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailyCollectionReportMSOAndTimeDeposits extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-collection-report-mso-and-time-deposits';

    protected ?string $heading = 'Daily Collection Report on MSO and Time Deposits';

    #[Computed]
    public function payments()
    {
        $mso = Saving::with('member')->whereDate('transaction_date', today())->get();
        $imprests = Imprest::with('member')->whereDate('transaction_date', today())->get();
        $love_gift = CashCollectiblePayment::with('member')->whereCashCollectibleId(6)->whereDate('transaction_date', today())->get();
        $ap = CashCollectiblePayment::with('member')->whereCashCollectibleId(7)->whereDate('transaction_date', today())->get();
        $time_deposits = TimeDeposit::with('member')->whereDate('transaction_date', today())->get();
        $time_deposits_withdrawal = TimeDeposit::with('member')->WhereDate('withdrawal_date', today())->get();

        return [
            'mso' => $mso,
            'imprests' => $imprests,
            'love_gift' => $love_gift,
            'ap' => $ap,
            'time_deposits' => $time_deposits,
            'time_deposits_withdrawal' => $time_deposits_withdrawal,
        ];
    }
}
