<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CashCollectibleSubscriptionResource\Pages;
use App\Models\CashCollectibleSubscription;
use App\Models\Member;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CashCollectibleSubscriptionResource extends Resource
{
    protected static ?string $model = CashCollectibleSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('account_id')
                    ->relationship('cash_collectible_account', 'name')
                    ->required(),
                Select::make('number_of_terms')
                    ->options([
                        1 => 1,
                        2 => 2,
                        4 => 4,
                        6 => 6,
                        12 => 12,
                    ])
                    ->required(),
                Select::make('member_id')
                    ->live()
                    ->afterStateUpdated(fn ($set, $state) => $set('payee', Member::find($state)?->full_name))
                    ->searchable()
                    ->preload()
                    ->relationship('member', 'full_name'),
                TextInput::make('payee')
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cash_collectible_account.name'),
                TextColumn::make('member.alt_full_name'),
                TextColumn::make('number_of_terms'),
                TextColumn::make('amount')->numeric(2),
                TextColumn::make('billable_amount')->numeric(2),
            ])
            ->filters([
                SelectFilter::make('cash_collectible_account')
                    ->relationship('cash_collectible_account', 'name'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
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
            'index' => Pages\ManageCashCollectibleSubscriptions::route('/'),
        ];
    }
}
