<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\MemberTypeResource\Pages\ManageMemberTypes;
use App\Filament\App\Resources\MemberTypeResource\Pages;
use App\Models\MemberType;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MemberTypeResource extends Resource
{
    protected static ?string $model = MemberType::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Management';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('manager') || auth()->user()->can('manage cbu');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(125),
                TextInput::make('default_number_of_shares')
                    ->required()
                    ->numeric(),
                TextInput::make('default_amount_subscribed')
                    ->required()
                    ->moneymask(),
                TextInput::make('minimum_initial_payment')
                    ->required()
                    ->moneymask(),
                TextInput::make('par_value')
                    ->required()
                    ->moneymask(),
                TextInput::make('surcharge_rate')
                    ->required()
                    ->numeric(),
                TextInput::make('minimum_initial_payment')
                    ->required()
                    ->moneymask(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('default_number_of_shares')->numeric(),
                TextColumn::make('default_amount_subscribed')->money('PHP'),
                TextColumn::make('minimum_initial_payment')->money('PHP'),
                TextColumn::make('par_value')->money('PHP'),
                TextColumn::make('surcharge_rate')
                    ->formatStateUsing(fn ($state) => $state * 100 .'%'),
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
            'index' => ManageMemberTypes::route('/'),
        ];
    }
}
