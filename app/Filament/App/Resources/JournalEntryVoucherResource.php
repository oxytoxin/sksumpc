<?php

    namespace App\Filament\App\Resources;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Actions\Action;
    use App\Filament\App\Resources\JournalEntryVoucherResource\Pages\ManageJournalEntryVouchers;
    use App\Filament\App\Resources\JournalEntryVoucherResource\Pages;
    use App\Models\Account;
    use App\Models\JournalEntryVoucher;
    use App\Models\Member;
    use App\Rules\BalancedBookkeepingEntries;
    use Auth;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Toggle;
    use Filament\Resources\Resource;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Table;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

    class JournalEntryVoucherResource extends Resource
    {
        protected static ?string $model = JournalEntryVoucher::class;

        protected static ?int $navigationSort = 6;

        protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

        public static function shouldRegisterNavigation(): bool
        {
            return Auth::user()->can('manage bookkeeping');
        }

        public static function form(Schema $schema): Schema
        {
            $generated_reference = JournalEntryVoucher::generateCode();
            return $schema
                ->components([
                    TextInput::make('name')->required(),
                    TextInput::make('address')->required(),
                    TextInput::make('reference_number')
                        ->default($generated_reference)
                        ->required()->unique('journal_entry_vouchers', 'reference_number'),
                    TextInput::make('voucher_number')
                        ->default($generated_reference)
                        ->required()->unique('journal_entry_vouchers', 'voucher_number'),
                    Toggle::make('compute_net')->label('Compute Net Amount')->default(false),
                    Textarea::make('description')->columnSpanFull()->required(),
                    Repeater::make('journal_entry_voucher_items')
                        ->rule(new BalancedBookkeepingEntries)
                        ->columnSpanFull()
                        ->table([
                            Repeater\TableColumn::make('Member')->width('13rem'),
                            Repeater\TableColumn::make('Account')->width('13rem'),
                            Repeater\TableColumn::make('Debit'),
                            Repeater\TableColumn::make('Credit'),
                        ])
                        ->reactive()
                        ->afterStateUpdated(function ($set, $get, $state) {
                            if ($get('compute_net')) {
                                $items = collect($state);
                                $cib = Account::getCashInBankGF();
                                $net_amount = $items->firstWhere('account_id', $cib?->id);
                                if ($net_amount) {
                                    $items = $items->filter(function ($i) use ($net_amount) {
                                        return $i['account_id'] != $net_amount['account_id'];
                                    });
                                    $net_amount['credit'] = $items->sum('debit') - $items->sum('credit');
                                    $items->push($net_amount);
                                }
                                $set('journal_entry_voucher_items', $items->toArray());
                            }
                        })
                        ->reorderable(false)
                        ->schema([
                            Select::make('member_id')
                                ->options(Member::pluck('full_name', 'id'))
                                ->label('Member')
                                ->searchable()
                                ->reactive()
                                ->preload(),
                            Select::make('account_id')
                                ->options(
                                    fn($get) => Account::withCode()->whereDoesntHave('children', fn($q) => $q->whereNull('member_id'))->where('member_id', $get('member_id') ?? null)->pluck('code', 'id')
                                )
                                ->searchable()
                                ->required()
                                ->label('Account'),
                            TextInput::make('debit')
                                ->moneymask(),
                            TextInput::make('credit')
                                ->moneymask(),
                        ]),
                ]);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    TextColumn::make('transaction_date')->date('F d, Y'),
                    TextColumn::make('voucher_type.name'),
                    TextColumn::make('journal_entry_voucher_items.account.number')
                        ->label('Account Numbers')
                        ->listWithLineBreaks(),
                    TextColumn::make('name'),
                    TextColumn::make('reference_number'),
                    TextColumn::make('description'),
                ])
                ->defaultSort('transaction_date', 'desc')
                ->filters([
                    DateRangeFilter::make('transaction_date')
                        ->format('m/d/Y')
                        ->displayFormat('MM/DD/YYYY'),
                ])
                ->filtersLayout(FiltersLayout::AboveContent)
                ->recordActions([
                    Action::make('view')
                        ->button()
                        ->color('success')
                        ->outlined()
                        ->modalHeading('JEV Preview')
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false)
                        ->modalContent(fn($record) => view('components.app.bookkeeper.reports.journal-entry-voucher-preview', ['journal_entry_voucher' => $record])),
                ])
                ->toolbarActions([]);
        }

        public static function getPages(): array
        {
            return [
                'index' => ManageJournalEntryVouchers::route('/'),
            ];
        }
    }
