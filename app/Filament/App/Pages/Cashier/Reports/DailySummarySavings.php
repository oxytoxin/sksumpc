<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailySummarySavings extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-savings';

    #[Computed]
    public function savings()
    {
        return auth()
            ->user()
            ->cashier_savings()
            ->with('savings_account')
            ->whereDate('transaction_date', today())
            ->get();
    }
}
