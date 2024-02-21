<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;
use App\Filament\App\Resources\BalanceForwardedSummaryResource\RelationManagers\BalanceForwardedEntriesRelationManager;
use App\Models\BalanceForwardedSummary;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class BalanceForwardedSummaryResource extends Resource
{
    protected static ?string $model = BalanceForwardedSummary::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public function getHeading(): string|Htmlable
    {
        return 'Loans Posting';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('month')
                    ->options(oxy_get_month_range())
                    ->default(today()->month)
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
            BalanceForwardedEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBalanceForwardedSummaries::route('/'),
            'create' => Pages\CreateBalanceForwardedSummary::route('/create'),
            'edit' => Pages\EditBalanceForwardedSummary::route('/{record}/edit'),
        ];
    }
}
