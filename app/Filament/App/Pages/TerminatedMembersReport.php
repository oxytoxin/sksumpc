<?php

    namespace App\Filament\App\Pages;

    use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
    use App\Models\Member;
    use Filament\Pages\Page;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;

    class TerminatedMembersReport extends Page implements HasTable
    {
        use HasSignatories, InteractsWithTable;

        protected static bool $shouldRegisterNavigation = false;

        protected static ?string $title = 'TERMINATED MEMBERS';

        protected string $view = 'filament.app.pages.terminated-members-report';

        public $report_title = 'REPORT ON TERMINATED MEMBERS';

        public function table(Table $table): Table
        {
            return $table
                ->query(
                    fn() => Member::query()
                        ->whereNotNull('terminated_at')
                        ->with(['membership_termination'])
                        ->orderBy('terminated_at', 'desc')
                )
                ->columns([
                    TextColumn::make('full_name')
                        ->label('Member Name')
                        ->searchable(),
                    TextColumn::make('terminated_at')
                        ->label('Date Terminated')
                        ->dateTime('m/d/Y'),
                    TextColumn::make('membership_termination.bod_resolution')
                        ->label('BOD Resolution'),
                    TextColumn::make('membership_termination.termination_voucher_number')
                        ->label('Reference (JEV/DV No.)'),
                    TextColumn::make('membership_termination.capital_amount_closed')
                        ->label('Total Capital Amount Closed')
                        ->numeric(decimalPlaces: 2),
                ])
                ->content(fn() => view('filament.app.pages.terminated-members-report-table', [
                    'signatories' => $this->getSignatories(),
                    'report_title' => $this->report_title,
                ]))
                ->filters([
                    SelectFilter::make('termination_year')
                        ->label('Year')
                        ->options(
                            fn() => Member::selectRaw('DISTINCT YEAR(terminated_at) as year')
                                ->whereNotNull('terminated_at')
                                ->orderBy('year', 'desc')
                                ->pluck('year', 'year')
                                ->map(fn($y) => (string) $y)
                        )
                        ->query(function ($query, $state) {
                            $query->when($state['value'], fn($q, $v) => $q->whereYear('terminated_at', $v));
                        }),
                    SelectFilter::make('member_type')
                        ->relationship('member_type', 'name'),
                    SelectFilter::make('division')
                        ->relationship('division', 'name'),
                ])
                ->filtersLayout(FiltersLayout::AboveContent)
                ->paginated(false);
        }
    }
