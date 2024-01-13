<?php

namespace App\Filament\App\Resources;

use App\Actions\Loans\ApproveLoanPosting;
use App\Filament\App\Resources\LoanResource\Pages;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Loan;
use App\Models\LoanType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LoanResource extends Resource
{
    use HasViewLoanDetailsActionGroup;

    protected static ?string $model = Loan::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Loan';

    protected static ?string $navigationLabel = 'Loans Posting';

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
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('loan_type.name'),
                TextColumn::make('gross_amount')->money('PHP'),
                TextColumn::make('deductions_amount')->money('PHP'),
                TextColumn::make('net_amount')->money('PHP'),
                IconColumn::make('posted')->boolean()->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('loan_type_id')
                    ->label('Loan Type')
                    ->options(LoanType::orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('posted')
                    ->options([
                        true => 'Posted',
                        false => 'Pending',
                    ]),
                Filter::make('date_applied')
                    ->form([
                        DatePicker::make('applied_from')->native(false),
                        DatePicker::make('applied_until')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['applied_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['applied_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Action::make('approve')
                    ->action(fn (Loan $record) => ApproveLoanPosting::run($record))
                    ->hidden(fn ($record) => $record->posted)
                    ->requiresConfirmation(),
                static::getStaticViewLoanDetailsActionGroup(),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record->loan_application]), true),
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
