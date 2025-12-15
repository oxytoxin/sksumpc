<?php

    namespace App\Filament\App\Resources;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Actions\EditAction;
    use Filament\Actions\BulkActionGroup;
    use Filament\Actions\DeleteBulkAction;
    use App\Filament\App\Resources\AccountResource\Pages\FinancialOperationEditor;
    use App\Filament\App\Resources\AccountResource\Pages\ListAccounts;
    use App\Filament\App\Resources\AccountResource\Pages\CreateAccount;
    use App\Filament\App\Resources\AccountResource\Pages\EditAccount;
    use App\Filament\App\Resources\AccountResource\Pages;
    use App\Models\Account;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Toggle;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Columns\IconColumn;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Table;

    class AccountResource extends Resource
    {
        protected static ?string $model = Account::class;

        protected static ?int $navigationSort = 1;

        protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

        public static function shouldRegisterNavigation(): bool
        {
            return auth()->user()->can('manage bookkeeping');
        }

        public static function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    Select::make('account_type_id')->required()
                        ->relationship(name: 'account_type', titleAttribute: 'name')
                        ->afterStateUpdated(fn($set, $state) => $set('children.*.account_type_id', $state))
                        ->reactive(),
                    TextInput::make('name')->required(),
                    TextInput::make('number')->required(),
                    Toggle::make('show_sum')->inline(false),
                    TextInput::make('sum_description')->required(),
                    Repeater::make('children')
                        ->schema([
                            Select::make('account_type_id')->required()
                                ->relationship(name: 'account_type', titleAttribute: 'name')
                                ->disabled()
                                ->dehydrated(true)
                                ->default(fn($get) => $get('../../account_type_id')),
                            TextInput::make('name')->required(),
                            TextInput::make('number')->required()
                                ->unique('accounts', 'number', ignoreRecord: true),
                        ])
                        ->table([
                            Repeater\TableColumn::make('Account'),
                            Repeater\TableColumn::make('Name'),
                            Repeater\TableColumn::make('Number'),
                        ])
                        ->default([])
                        ->columnSpanFull()
                        ->relationship(),

                ])->columns(3);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->query(Account::withCode()->whereNull('member_id'))
                ->columns([
                    TextColumn::make('fullname')
                        ->label('Name')
                        ->searchable(),
                    TextColumn::make('number')
                        ->searchable(),
                    IconColumn::make('show_sum')
                        ->label('Show Total on FS')
                        ->boolean(),
                    TextColumn::make('sum_description')
                        ->label('Total String'),
                ])
                ->filters([
                    //
                ])
                ->recordActions([
                    EditAction::make(),
                ])
                ->toolbarActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ]);
        }

        public static function getRelations(): array
        {
            return [
                //
            ];
        }

        public static function getPages(): array
        {
            return [
                'financial-operation-editor' => FinancialOperationEditor::route('financial-operation-editor'),
                'index' => ListAccounts::route('/'),
                'create' => CreateAccount::route('/create'),
                'edit' => EditAccount::route('/{record}/edit'),
            ];
        }
    }
