<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TimeDeposit;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TimeDepositResource\Pages;
use App\Filament\App\Resources\TimeDepositResource\RelationManagers;
use App\Filament\App\Resources\TimeDepositResource\Pages\ListTimeDeposits;
use Filament\Forms\Components\Section;

class TimeDepositResource extends Resource
{
    protected static ?string $model = TimeDeposit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Transactions History';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->canAny(['manage bookkeeping']);
    }

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
                TextColumn::make('member.full_name'),
                TextColumn::make('reference_number'),
                TextColumn::make('amount')->money('PHP'),
                TextColumn::make('maturity_amount')->money('PHP'),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('withdrawal_date')->date('F d, Y'),
                TextColumn::make('cashier.name')->label('Cashier'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        Section::make('Transaction Date')
                            ->schema([
                                DatePicker::make('from')->default(today())->native(false),
                                DatePicker::make('until')->default(today())->native(false),
                            ]),
                        Section::make('Withdrawal Date')
                            ->schema([
                                DatePicker::make('withdrawal_from')->native(false),
                                DatePicker::make('withdrawal_until')->native(false),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            )
                            ->when(
                                $data['withdrawal_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('withdrawal_date', '>=', $date),
                            )
                            ->when(
                                $data['withdrawal_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('withdrawal_date', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('transaction_date', 'desc')
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
            'index' => Pages\ListTimeDeposits::route('/'),
        ];
    }
}
