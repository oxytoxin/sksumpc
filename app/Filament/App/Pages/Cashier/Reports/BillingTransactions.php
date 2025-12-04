<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Enums\FromBillingTypes;
use App\Enums\TransactionTypes;
use App\Models\Member;
use App\Models\Transaction;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class BillingTransactions extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.billing-transactions';

    protected static ?string $title = 'BILLING TRANSACTIONS';

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query()->whereNotNull('from_billing_type')->where('transaction_type_id', TransactionTypes::CRJ->value))
            ->content(function ($livewire) {
                return view('filament.app.pages.cashier.reports.billing-transactions-report-table', [
                    'signatories' => $this->signatories,
                    'report_title' => "REPORT ON MEMBERS' BILLING PAYMENTS",
                ]);
            })
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member.member_type', 'name'),
                SelectFilter::make('member_subtype')
                    ->relationship('member.member_subtype', 'name'),
                SelectFilter::make('division')
                    ->relationship('member.division', 'name'),
                SelectFilter::make('patronage_status')
                    ->relationship('member.patronage_status', 'name'),
                SelectFilter::make('gender')
                    ->relationship('member.gender', 'name'),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Terminated',
                    ])
                    ->default(1)
                    ->query(
                        fn ($query, $state) => $query
                            ->when($state['value'] == 1, fn ($q) => $q->whereRelation('member', 'terminated_at', null))
                            ->when($state['value'] == 2, fn ($q) => $q->whereRelation('member', 'terminated_at', '!=', null))
                    ),
                SelectFilter::make('civil_status')
                    ->relationship('member.civil_status', 'name'),
                SelectFilter::make('occupation')
                    ->relationship('member.occupation', 'name'),
                SelectFilter::make('highest_educational_attainment')
                    ->label('Highest Educational Attainment')
                    ->options(Member::whereNotNull('highest_educational_attainment')
                        ->distinct('highest_educational_attainment')
                        ->pluck('highest_educational_attainment', 'highest_educational_attainment'))
                    ->searchable()
                    ->preload()
                    ->query(
                        fn ($query, $state) => $query
                            ->when($state['value'], fn ($q, $v) => $q->whereRelation('member', 'highest_educational_attainment', $v))
                    ),
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
                SelectFilter::make('from_billing_type')
                    ->options(FromBillingTypes::options()),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    public function mount()
    {
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }
}
