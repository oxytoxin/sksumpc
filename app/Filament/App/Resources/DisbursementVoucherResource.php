<?php

    namespace App\Filament\App\Resources;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Actions\EditAction;
    use Filament\Actions\Action;
    use App\Filament\App\Resources\DisbursementVoucherResource\Pages\ManageDisbursementVouchers;
    use App\Enums\AccountIds;
    use App\Filament\App\Resources\DisbursementVoucherResource\Pages;
    use App\Models\Account;
    use App\Models\DisbursementVoucher;
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
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

    class DisbursementVoucherResource extends Resource
    {
        protected static ?string $model = DisbursementVoucher::class;

        protected static ?int $navigationSort = 5;

        protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

        public static function shouldRegisterNavigation(): bool
        {
            return Auth::user()->can('manage bookkeeping');
        }

        public static function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    TextInput::make('name')->required(),
                    TextInput::make('address')->required(),
                    TextInput::make('reference_number')->required(),
                    TextInput::make('check_number'),
                    TextInput::make('voucher_number')->required(),
                    Toggle::make('compute_net')->label('Compute Net Amount')->default(true),
                    Textarea::make('description')->columnSpanFull()->required(),
                    Repeater::make('disbursement_voucher_items')
                        ->columnSpanFull()
                        ->table([
                            Repeater\TableColumn::make('Member')->width('13rem'),
                            Repeater\TableColumn::make('Account')->width('13rem'),
                            Repeater\TableColumn::make('Debit'),
                            Repeater\TableColumn::make('Credit'),
                        ])
                        ->rule(new BalancedBookkeepingEntries)
                        ->reactive()
                        ->reorderable(false)
                        ->afterStateUpdated(function ($set, $get, $state) {
                            if ($get('compute_net')) {
                                $items = collect($state);
                                $cib = Account::getCashInBankGF();
                                $net_amount = $items->firstWhere('account_id', $cib?->id);
                                if ($net_amount) {
                                    $items = $items->filter(fn($i) => $i['account_id'] != $net_amount['account_id']);
                                    $net_amount['credit'] = $items->sum('debit') - $items->sum('credit');
                                    $items->push($net_amount);
                                }
                                $set('disbursement_voucher_items', $items->toArray());
                            }
                        })
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
                    TextColumn::make('disbursement_voucher_items.account.number')
                        ->label('Account Numbers')
                        ->listWithLineBreaks(),
                    TextColumn::make('name'),
                    TextColumn::make('reference_number'),
                    TextColumn::make('check_number'),
                    TextColumn::make('voucher_number'),
                    TextColumn::make('description')
                        ->wrap()
                        ->limit(50),
                ])
                ->filters([
                    DateRangeFilter::make('transaction_date')
                        ->format('m/d/Y')
                        ->displayFormat('MM/DD/YYYY'),
                    SelectFilter::make('Type')
                        ->options([
                            'mso' => 'MSO',
                            'loan' => 'LOAN',
                            'others' => 'OTHERS',
                        ])
                        ->query(function ($query, $state) {
                            $query
                                ->when($state['value'] == 'mso', function ($q, $v) {
                                    $q->whereHas('disbursement_voucher_items', function ($q) {
                                        $q->whereHas('item_account', function ($q) {
                                            $q->whereRelation('ancestorsAndSelf', 'id', AccountIds::MSO->value);
                                        });
                                    })->whereDoesntHave('disbursement_voucher_items', function ($q) {
                                        $q->whereHas('item_account', function ($q) {
                                            $q->whereRelation('ancestorsAndSelf', 'id', AccountIds::LOAN_RECEIVABLES->value);
                                        });
                                    });
                                })->when($state['value'] == 'loan', function ($q, $v) {
                                    $q->whereHas('disbursement_voucher_items', function ($q) {
                                        $q->whereHas('item_account', function ($q) {
                                            $q->whereRelation('ancestorsAndSelf', 'id', AccountIds::LOAN_RECEIVABLES->value);
                                        });
                                    });
                                })
                                ->when($state['value'] == 'others', function ($q, $v) {
                                    $q->whereDoesntHave('disbursement_voucher_items', function ($q) {
                                        $q->whereHas('item_account', function ($q) {
                                            $q->whereHas('ancestorsAndSelf', function ($q) {
                                                $q->whereIn('id', [AccountIds::MSO->value, AccountIds::LOAN_RECEIVABLES->value]);
                                            });
                                        });
                                    });
                                });
                        }),
                ])
                ->filtersLayout(FiltersLayout::AboveContent)
                ->recordActions([
                    EditAction::make()
                        ->schema([
                            TextInput::make('voucher_number'),
                            TextInput::make('check_number'),
                        ]),
                    Action::make('view')
                        ->button()
                        ->color('success')
                        ->outlined()
                        ->modalHeading('Disbursement Voucher Preview')
                        ->modalWidth('5xl')
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false)
                        ->modalContent(fn($record) => view('components.app.bookkeeper.reports.disbursement-voucher-preview', ['disbursement_voucher' => $record])),

                ])
                ->toolbarActions([]);
        }

        public static function getPages(): array
        {
            return [
                'index' => ManageDisbursementVouchers::route('/'),
            ];
        }
    }
