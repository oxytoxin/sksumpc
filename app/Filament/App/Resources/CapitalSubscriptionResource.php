<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CapitalSubscriptionResource\Pages;
use App\Filament\App\Resources\CapitalSubscriptionResource\RelationManagers;
use App\Models\CapitalSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CapitalSubscriptionResource extends Resource
{
    protected static ?string $model = CapitalSubscription::class;

    protected static ?string $navigationGroup = 'Share Capital';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.alt_full_name')->sortable(),
                TextColumn::make('amount_subscribed')->money('PHP'),
                TextColumn::make('monthly_payment')->money('PHP'),
                TextColumn::make('outstanding_balance')->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->defaultSort('member.alt_full_name')
            ->actions([])
            ->bulkActions([]);
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
            'index' => Pages\ListCapitalSubscriptions::route('/'),
            'reports.top-ten-highest-cbu' => Pages\Reports\TopTenHighestCbuReport::route('/reports/top-ten-highest-cbu')
        ];
    }
}
