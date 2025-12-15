<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages\ListBalanceForwardedSummaries;
use App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages\CreateBalanceForwardedSummary;
use App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages\EditBalanceForwardedSummary;
use App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;
use App\Filament\App\Resources\BalanceForwardedSummaryResource\RelationManagers\BalanceForwardedEntriesRelationManager;
use App\Models\BalanceForwardedSummary;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BalanceForwardedSummaryResource extends Resource
{
    protected static ?string $model = BalanceForwardedSummary::class;

    protected static ?int $navigationSort = 4;

    protected static string | \UnitEnum | null $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('month')
                    ->options(oxy_get_month_range())
                    ->default(today()->subMonthNoOverflow()->month)
                    ->required(),
                Select::make('year')
                    ->options(oxy_get_year_range())
                    ->default(today()->year)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('generated_date')
                    ->date('F Y'),
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
            BalanceForwardedEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBalanceForwardedSummaries::route('/'),
            'create' => CreateBalanceForwardedSummary::route('/create'),
            'edit' => EditBalanceForwardedSummary::route('/{record}/edit'),
        ];
    }
}
