<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CashCollectibleBillingResource\Pages;
use App\Filament\App\Resources\CashCollectibleBillingResource\RelationManagers;
use App\Models\CashCollectibleBilling;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashCollectibleBillingResource extends Resource
{
    protected static ?string $model = CashCollectibleBilling::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListCashCollectibleBillings::route('/'),
            'create' => Pages\CreateCashCollectibleBilling::route('/create'),
            'edit' => Pages\EditCashCollectibleBilling::route('/{record}/edit'),
        ];
    }
}
