<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;

class DailySummaryImprests extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-imprests';
}
