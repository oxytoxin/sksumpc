<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\LoanPayment;
use App\Models\LoanType;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class LoanPaymentsReport extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.loan-payments-report';

    protected ?string $heading = 'Daily Collection Report on Loans';

    public $report_title;

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanPayment::query())
            ->content(fn () => view('filament.app.pages.cashier.reports.loan-payments-report-table', [
                'signatories' => $this->signatories,
                'report_title' => $this->report_title,
            ]))
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->defaultToday()
                    ->displayFormat('MM/DD/YYYY'),
                SelectFilter::make('loan_type_id')
                    ->options(LoanType::pluck('name', 'id'))
                    ->multiple()
                    ->query(fn ($query, $state) => $query->when(count($state['values'] ?? []), fn ($q) => $q->whereHas('loan', fn ($q) => $q->whereIn('loan_type_id', $state['values']))))
                    ->label('Loan Type'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
