<?php

    namespace App\Filament\App\Pages\Cashier;

    use App\Filament\App\Pages\Cashier\Traits\HasReceipt;
    use App\Models\CashierTransaction;
    use Auth;
    use Filament\Actions\Action;
    use Filament\Actions\Concerns\InteractsWithActions;
    use Filament\Actions\Contracts\HasActions;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Pages\Page;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
    use stdclass;

    class TransactionHistoryPage extends Page implements HasActions, HasForms, HasTable
    {
        use HasReceipt, InteractsWithActions, InteractsWithForms, InteractsWithTable;

        protected static ?string $navigationLabel = 'Transaction History';

        protected static ?int $navigationSort = 2;

        protected static string|\UnitEnum|null $navigationGroup = 'Cashier';

        protected string $view = 'filament.app.pages.cashier.transaction-history-page';

        public static function shouldRegisterNavigation(): bool
        {
            return Auth::user()->can('manage payments');
        }

        public function table(Table $table): Table
        {
            return $table
                ->query(
                    CashierTransaction::query()
                        ->with(['user', 'member'])
                        ->orderByDesc('transaction_date')
                        ->orderByDesc('id')
                )
                ->columns([
                    TextColumn::make('#')->state(
                        static function (HasTable $livewire, stdclass $rowLoop): string {
                            return (string) (
                                $rowLoop->iteration +
                                ($livewire->getTableRecordsPerPage() * (
                                        $livewire->getTablePage() - 1
                                    ))
                            );
                        }
                    ),
                    TextColumn::make('transaction_date')->date('F d, Y'),
                    TextColumn::make('member.full_name')->label('Member')->default('—'),
                    TextColumn::make('total_amount')
                        ->label('Total Amount')
                        ->state(fn($record) => collect($record->transactions)->sum('amount'))
                        ->money('PHP'),
                    TextColumn::make('user.name')->label('Cashier'),
                    TextColumn::make('created_at')->dateTime('F d, Y h:i A'),
                ])
                ->filters([
                    DateRangeFilter::make('transaction_date')
                        ->format('m/d/Y')
                        ->displayFormat('MM/DD/YYYY'),
                    SelectFilter::make('member_id')
                        ->label('Member')
                        ->relationship('member', 'full_name')
                        ->searchable()
                        ->preload(),
                ])
                ->filtersLayout(FiltersLayout::AboveContent)
                ->recordActions([
                    Action::make('reprint')
                        ->icon('heroicon-o-printer')
                        ->label('Reprint Receipt')
                        ->action(function (CashierTransaction $record) {
                            $receiptTransactions = collect($record->transactions)->map(fn($t) => array_merge($t, [
                                'transaction_date' => $record->transaction_date->format('m/d/Y'),
                            ]))->toArray();

                            $this->replaceMountedAction('receipt', ['transactions' => $receiptTransactions]);
                        }),
                ]);
        }

        public function mount()
        {
            data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
        }
    }
