<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class DailySummarySavings extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-savings';
}
