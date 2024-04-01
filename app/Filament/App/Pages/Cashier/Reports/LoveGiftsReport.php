<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\LoveGift;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;

class LoveGiftsReport extends Page implements HasTable
{
    use HasReport, HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.love-gifts-report';

    private function getReportQuery()
    {
        return LoveGift::query();
    }
}
