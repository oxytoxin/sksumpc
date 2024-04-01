<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\CapitalSubscriptionPayment;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;

class ShareCapitalPaymentsReport extends Page implements HasTable
{
    use HasReport, HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.share-capital-payments-report';

    private function getReportQuery()
    {
        return CapitalSubscriptionPayment::query();
    }
}
