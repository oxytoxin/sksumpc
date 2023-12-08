<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailySummaryTimeDeposits extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-time-deposits';

    #[Computed]
    public function time_deposits()
    {
        return  auth()
            ->user()
            ->cashier_time_deposits()
            ->with('member')
            ->whereDate('transaction_date', today())
            ->orWhereDate('withdrawal_date', today())
            ->get();
    }
}
