<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;

class DailySummaryCashCollectibles extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-cash-collectibles';
}
