<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use App\Filament\App\Resources\CapitalSubscriptionPaymentResource\Pages\ListCapitalSubscriptionPayments;
use App\Filament\App\Resources\CapitalSubscriptionPaymentResource\Pages;
use App\Models\CapitalSubscriptionPayment;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class CapitalSubscriptionPaymentResource extends Resource
{
    protected static ?string $model = CapitalSubscriptionPayment::class;

    protected static ?string $modelLabel = 'Share Capital Payments';

    protected static string | \BackedEnum | null $navigationIcon = 'icon-share-capital';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Transactions History';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('capital_subscription.member.full_name'),
                TextColumn::make('reference_number'),
                TextColumn::make('amount')->money('PHP'),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('cashier.name')->label('Cashier'),
            ])
            ->filters([
                Filter::dateRange('transaction_date'),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
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
            'index' => ListCapitalSubscriptionPayments::route('/'),
        ];
    }
}
