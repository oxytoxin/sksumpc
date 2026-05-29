<?php

    namespace App\Filament\App\Pages;

    use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
    use App\Models\SavingsAccount;
    use Filament\Pages\Page;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

    class ClosedSavingsReport extends Page implements HasTable
    {
        use HasSignatories, InteractsWithTable;

        protected static bool $shouldRegisterNavigation = false;

        protected static ?string $title = 'CLOSED SAVINGS ACCOUNTS';

        protected string $view = 'filament.app.pages.closed-savings-report';

        public $report_title = 'REPORT ON CLOSED SAVINGS ACCOUNTS';

        public function table(Table $table): Table
        {
            return $table
                ->query(
                    fn() => SavingsAccount::closed()
                        ->with(['member'])
                        ->orderBy('closed_at', 'desc')
                )
                ->columns([
                    TextColumn::make('number')
                        ->label('Account Number')
                        ->searchable(),
                    TextColumn::make('member.mpc_code')
                        ->label('MPC Code')
                        ->searchable(),
                    TextColumn::make('member.full_name')
                        ->label('Member Name')
                        ->searchable(),
                    TextColumn::make('closed_at')
                        ->label('Date Closed')
                        ->dateTime('m/d/Y'),
                    TextColumn::make('close_remarks')
                        ->label('Remarks')
                        ->default('—')
                        ->limit(50),
                ])
                ->content(fn() => view('filament.app.pages.closed-savings-report-table', [
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
                    DateRangeFilter::make('closed_at')
                        ->label('Date Closed')
                        ->format('m/d/Y')
                        ->displayFormat('MM/DD/YYYY'),
                ])
                ->filtersLayout(FiltersLayout::AboveContent)
                ->paginated(false);
        }

        public function mount()
        {
            data_set($this, 'tableFilters.closed_at.closed_at', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
        }
    }
