<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LoanPayment;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\LoanPaymentResource\Pages;
use App\Filament\App\Resources\LoanPaymentResource\RelationManagers;
use App\Filament\App\Resources\LoanPaymentResource\Pages\EditLoanPayment;
use App\Filament\App\Resources\LoanPaymentResource\Pages\ListLoanPayments;
use App\Filament\App\Resources\LoanPaymentResource\Pages\CreateLoanPayment;

class LoanPaymentResource extends Resource
{
    protected static ?string $model = LoanPayment::class;

    protected static ?string $navigationIcon = 'icon-loan';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Transactions History';

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
                TextColumn::make('loan.member.full_name'),
                TextColumn::make('loan.loan_type.code'),
                TextColumn::make('loan.reference_number')->label('Loan reference'),
                TextColumn::make('reference_number'),
                TextColumn::make('amount')->label('Amount Paid')->money('PHP'),
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
            'index' => Pages\ListLoanPayments::route('/'),
        ];
    }
}
