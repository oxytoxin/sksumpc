<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Loan;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\LoanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\LoanResource\Pages\ListLoans;
use App\Filament\App\Resources\LoanResource\RelationManagers;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'icon-loan';

    protected static ?int $navigationSort = 6;

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
                TextColumn::make('member.full_name'),
                TextColumn::make('gross_amount')->money('PHP'),
                TextColumn::make('deductions_amount')->money('PHP'),
                TextColumn::make('net_amount')->money('PHP'),
                IconColumn::make('posted')->boolean()->alignCenter()
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')->native(false),
                        DatePicker::make('until')->native(false),
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
            ->actions([
                Action::make('approve')
                    ->action(fn ($record) => $record->update([
                        'posted' => true
                    ]))
                    ->hidden(fn ($record) => $record->posted)
                    ->requiresConfirmation(),
                Action::make('loan_application')
                    ->label('View Application')
                    ->button()
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.view', ['record' => $record->loan_application])),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record->loan_application]), true)
            ])
            ->bulkActions([])
            ->emptyStateActions([]);
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
            'index' => Pages\ListLoans::route('/'),
        ];
    }
}
