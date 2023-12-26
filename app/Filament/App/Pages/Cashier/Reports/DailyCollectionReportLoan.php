<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\LoanPayment;
use App\Models\LoanType;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DailyCollectionReportLoan extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-collection-report-loan';

    protected ?string $heading = 'Daily Collection Report on Loans';

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanPayment::query())
            ->content(fn () => view('filament.app.views.daily-collection-report-loan-table', [
                'signatories' => $this->signatories,
                'loan_types' => LoanType::findMany($this->tableFilters['loan_type_ids']['values']),
            ]))
            ->filters([
                Filter::dateRange('release_date'),
                SelectFilter::make('loan_type_ids')
                    ->options(LoanType::pluck('name', 'id'))
                    ->multiple()
                    ->default(LoanType::pluck('id')->toArray())
                    ->label('Loan Types')
                    ->columnSpanFull()
                    ->query(fn ($query) => $query),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    protected function getSignatories()
    {
        $treasurer = User::whereRelation('roles', 'name', 'treasurer')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => auth()->user()->name,
                'position' => 'Teller/Cashier',
            ],
            [],
            [
                'action' => 'Received by:',
                'name' => $treasurer?->name ?? 'DESIREE G. LEGASPI',
                'position' => 'Treasurer',
            ],
        ];
    }
}
