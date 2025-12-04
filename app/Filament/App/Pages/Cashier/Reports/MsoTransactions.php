<?php

namespace App\Filament\App\Pages\Cashier\Reports;

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

class MsoTransactions extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'MSO TRANSACTIONS';

    protected static string $view = 'filament.app.pages.cashier.reports.mso-transactions';

    public $report_title = "REPORT ON MEMBERS' MSO TRANSACTIONS";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->where('transaction_type_id', TransactionTypes::CRJ->value)
            )
            ->content(fn () => view('filament.app.pages.cashier.reports.mso-transactions-report-table', [
                'signatories' => $this->signatories,
                'report_title' => $this->report_title,
            ]))
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
                SelectFilter::make('mso_type')
                    ->label('MSO Type')
                    ->multiple()
                    ->options([
                        'savings' => 'SAVINGS',
                        'imprest' => 'IMPREST',
                        'love_gift' => 'LOVE GIFT',
                        'time_deposit' => 'TIME DEPOSIT',
                    ])
                    ->query(function ($query, $state) {
                        $tags = collect();
                        $state_values = collect($state['values']);
                        if ($state_values->contains('savings')) {
                            $tags = $tags->merge(['member_savings_deposit', 'member_savings_withdrawal']);
                        }
                        if ($state_values->contains('imprest')) {
                            $tags = $tags->merge(['member_imprest_deposit', 'member_imprest_withdrawal']);
                        }
                        if ($state_values->contains('love_gift')) {
                            $tags = $tags->merge(['member_love_gift_deposit', 'member_love_gift_withdrawal']);
                        }
                        if ($state_values->contains('time_deposit')) {
                            $tags = $tags->merge(['member_time_deposit']);
                        }
                        if ($state['values']) {
                            return $query->whereIn('tag', $tags->toArray());
                        } else {
                            return $query->whereIn('tag', ['member_savings_deposit', 'member_savings_withdrawal', 'member_imprest_deposit', 'member_imprest_withdrawal', 'member_love_gift_deposit', 'member_love_gift_withdrawal', 'member_time_deposit']);
                        }
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    public function mount()
    {
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }
}
