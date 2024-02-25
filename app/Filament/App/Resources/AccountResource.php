<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AccountResource\Pages;
use App\Models\Account;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('number')->required(),
                TableRepeater::make('children')
                    ->schema([
                        Select::make('account_type_id')->required()
                            ->relationship(name: 'account_type', titleAttribute: 'name'),
                        TextInput::make('name')->required(),
                        TextInput::make('number')->required()
                            ->unique('accounts', 'number', ignoreRecord: true),
                    ])
                    ->columnSpanFull()
                    ->hideLabels()
                    ->relationship(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Account::withCode()->isRoot()->whereNull('member_id'))
            ->columns([
                TextColumn::make('fullname')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('number')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
