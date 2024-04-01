<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Saving;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;

class SavingsReport extends Page implements HasTable
{
    use HasReport, HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.savings-report';

    private function getReportQuery()
    {
        return Saving::query();
    }
}
