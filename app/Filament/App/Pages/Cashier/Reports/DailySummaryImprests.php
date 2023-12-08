<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailySummaryImprests extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-imprests';

    #[Computed]
    public function imprests()
    {
        return auth()
            ->user()
            ->cashier_imprests()
            ->with('member')
            ->whereDate('transaction_date', today())
            ->get();
    }
}
