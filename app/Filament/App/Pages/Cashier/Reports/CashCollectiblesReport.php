<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\CashCollectible;
use App\Models\CashCollectiblePayment;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class CashCollectiblesReport extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.cash-collectibles-report';

    public $report_title;

    public function mount()
    {
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CashCollectiblePayment::query())
            ->content(fn () => view('filament.app.pages.cashier.reports.cash-collectible-payments-report-table', [
                'signatories' => $this->signatories,
                'report_title' => $this->report_title,
            ]))
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
                SelectFilter::make('cash_collectible_id')
                    ->options(CashCollectible::pluck('name', 'id'))
                    ->multiple()
                    ->query(fn ($query, $state) => $query->when(count($state['values'] ?? []), fn ($q) => $q->whereIn('cash_collectible_id', $state['values'])))
                    ->label('Cash Collectible'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
