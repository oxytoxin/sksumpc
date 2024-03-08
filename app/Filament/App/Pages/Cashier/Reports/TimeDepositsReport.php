<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\LoanType;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\TimeDeposit;
use App\Models\Transaction;
use Livewire\Attributes\Computed;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class TimeDepositsReport extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.time-deposits-report';

    public $report_title;

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query()->whereTag('member_time_deposit'))
            ->content(fn () => view('filament.app.pages.cashier.reports.time-deposits-report-table', [
                'signatories' => $this->signatories,
                'report_title' => $this->report_title,
            ]))
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->defaultToday()
                    ->displayFormat('MM/DD/YYYY'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
