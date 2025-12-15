<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\LoanTypeResource\Pages\ManageLoanTypes;
use App\Filament\App\Resources\LoanTypeResource\Pages;
use App\Models\LoanType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoanTypeResource extends Resource
{
    protected static ?string $model = LoanType::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Loan Schedule';

    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(125),
                TextInput::make('name')
                    ->required()
                    ->maxLength(125),
                TextInput::make('interest_rate')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                TextInput::make('surcharge_rate')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                TextInput::make('service_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                TextInput::make('cbu_common')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                TextInput::make('imprest')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                TextInput::make('insurance')
                    ->required()
                    ->numeric()
                    ->default(0.0000),
                Toggle::make('has_monthly_amortization')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('interest_rate')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state * 100 .'%')
                    ->sortable(),
                TextColumn::make('surcharge_rate')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state * 100 .'%')
                    ->sortable(),
                TextColumn::make('service_fee')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state * 100 .'%')
                    ->sortable(),
                TextColumn::make('cbu_common')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state * 100 .'%')
                    ->sortable(),
                TextColumn::make('imprest')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state * 100 .'%')
                    ->sortable(),
                TextColumn::make('insurance')
                    ->numeric(4)
                    ->formatStateUsing(fn ($state) => $state * 100 .'%')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLoanTypes::route('/'),
        ];
    }
}
