<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Imprest;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Attributes\Computed;

class ImprestsReport extends Page implements HasTable
{
    use HasSignatories, HasReport;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.imprests-report';

    private function getReportQuery()
    {
        return Imprest::query();
    }
}
