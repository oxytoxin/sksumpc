<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Imprest;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ImprestResource\Pages;
use App\Filament\App\Resources\ImprestResource\RelationManagers;
use App\Filament\App\Resources\ImprestResource\Pages\EditImprest;
use App\Filament\App\Resources\ImprestResource\Pages\ListImprests;
use App\Filament\App\Resources\ImprestResource\Pages\CreateImprest;

class ImprestResource extends Resource
{
    protected static ?string $model = Imprest::class;

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
                TextColumn::make('deposit')->money('PHP'),
                TextColumn::make('withdrawal')->money('PHP'),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('cashier.name')->label('Cashier'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')->default(today())->native(false),
                        DatePicker::make('until')->default(today())->native(false),
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
                            );
                    })
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
            'index' => Pages\ListImprests::route('/'),
        ];
    }
}
