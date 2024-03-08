<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Saving;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Concerns\InteractsWithTable;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class SavingsReport extends Page implements HasTable
{
    use HasSignatories, HasReport;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.savings-report';

    private function getReportQuery()
    {
        return Saving::query();
    }
}
