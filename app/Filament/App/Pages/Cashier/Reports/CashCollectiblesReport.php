<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Account;
use App\Models\CashCollectible;
use App\Models\CashCollectiblePayment;
use App\Models\Transaction;
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
            ->query(
                Transaction::query()
                    ->whereRelation('account', fn($query) => $query->whereIn('parent_id', [16, 18, 91]))
            )
            ->content(fn() => view('filament.app.pages.cashier.reports.cash-collectible-payments-report-table', [
                'signatories' => $this->signatories,
                'report_title' => $this->report_title,
            ]))
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
                SelectFilter::make('account_id')
                    ->options(Account::withCode()->whereIn('parent_id', [16, 18, 91])->pluck('code', 'id'))
                    ->multiple()
                    ->label('Cash Collectible'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
