<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TotalLoanReleasedReport extends Page implements HasTable
{
    use InteractsWithTable, HasSignatories;

    protected static string $view = 'filament.app.pages.total-loan-released-report';

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(Loan::query()->wherePosted(true))
            ->content(fn () => view('filament.app.views.total-loan-released-report-table', [
                'signatories' => $this->signatories,
                'loan_types' => LoanType::findMany($this->tableFilters['loan_type_ids']['values'])
            ]))
            ->filters([
                Filter::dateRange('release_date'),
                SelectFilter::make('loan_type_ids')
                    ->options(LoanType::pluck('name', 'id'))
                    ->multiple()
                    ->default(LoanType::pluck('id')->toArray())
                    ->label('Loan Types')
                    ->columnSpanFull()
                    ->query(fn ($query) => $query)
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    protected function getSignatories()
    {
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => auth()->user()->name,
                'position' => 'Loan Officer'
            ],
            [
                'action' => 'Noted:',
                'name' => $manager?->name ?? 'FLORA C. DAMANDAMAN',
                'position' => 'Manager'
            ],
        ];
    }
}
