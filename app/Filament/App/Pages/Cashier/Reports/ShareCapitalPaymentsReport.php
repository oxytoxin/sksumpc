<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\CapitalSubscriptionPayment;
use App\Models\LoanType;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\LoanPayment;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Attributes\Computed;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ShareCapitalPaymentsReport extends Page implements HasTable
{
    use HasSignatories, HasReport;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.share-capital-payments-report';

    private function getReportQuery()
    {
        return CapitalSubscriptionPayment::query();
    }
}
