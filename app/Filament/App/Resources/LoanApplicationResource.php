<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\LoanApplicationResource\Pages;
use App\Filament\App\Resources\LoanApplicationResource\RelationManagers;
use App\Models\LoanApplication;
use App\Models\LoanType;
use App\Oxytoxin\LoansProvider;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class LoanApplicationResource extends Resource
{
    protected static ?string $model = LoanApplication::class;

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(fn ($record) => [
            TextEntry::make('reference_number'),
            TextEntry::make('member.full_name'),
            TextEntry::make('loan_type.name'),
            TextEntry::make('number_of_terms'),
            TextEntry::make('priority_number'),
            TextEntry::make('desired_amount')->money('PHP'),
            TextEntry::make('transaction_date')->date('m/d/Y'),
            TextEntry::make('purpose'),
            Section::make('Loan Details')
                ->visible(fn ($record) => $record->loan)
                ->schema([
                    TextEntry::make('loan.gross_amount')->money('PHP')->label('Gross Amount'),
                    ViewEntry::make('loan.deductions')
                        ->view('infolists.components.loan-deductions-entry', ['deductions' => $record->loan?->deductions]),
                    TextEntry::make('loan.deductions_amount')->money('PHP')->label('Deductions Amount'),
                    TextEntry::make('loan.net_amount')->money('PHP')->label('Net Amount'),
                    TextEntry::make('loan.interest_rate')->formatStateUsing(fn ($state) => str($state * 100)->append('%'))->label('Interest Rate'),
                    TextEntry::make('loan.interest')->money('PHP')->label('Interest Amount'),
                    TextEntry::make('loan.monthly_payment')->money('PHP')->label('Monthly Payment'),
                    TextEntry::make('loan.release_date')->date('m/d/Y')->label('Release Date'),

                ])
        ])->columns(1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->relationship('member', 'full_name')
                    ->searchable()
                    ->preload(50)
                    ->required(),
                Select::make('loan_type_id')
                    ->relationship('loan_type', 'name')
                    ->required(),
                Select::make('number_of_terms')
                    ->options(LoansProvider::LOAN_TERMS)
                    ->default(12)
                    ->live(),
                TextInput::make('priority_number'),
                TextInput::make('desired_amount')->moneymask()->required(),
                DatePicker::make('transaction_date')->required()->native(false)->default(today()),
                TextInput::make('purpose')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('transaction_date')->date('m/d/Y')->label('Date Applied'),
                TextColumn::make('loan_type.name'),
                TextColumn::make('desired_amount')->money('PHP'),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        LoanApplication::STATUS_PROCESSING => 'For Approval',
                        LoanApplication::STATUS_APPROVED => 'Approved',
                        LoanApplication::STATUS_DISAPPROVED => 'Disapproved',
                        LoanApplication::STATUS_POSTED => 'Posted',
                    })
                    ->colors([
                        'warning' => LoanApplication::STATUS_PROCESSING,
                        'success' => LoanApplication::STATUS_APPROVED,
                        'danger' => LoanApplication::STATUS_DISAPPROVED,
                        'success' => LoanApplication::STATUS_POSTED,
                    ])
                    ->badge(),
            ])
            ->defaultLoanApplicationFilters()
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn ($record) => auth()->user()->can('manage loans') && $record->status == LoanApplication::STATUS_PROCESSING),
                Action::make('Approve')
                    ->action(function ($record, $data) {
                        $record->update([
                            'status' => LoanApplication::STATUS_APPROVED,
                        ]);
                        Notification::make()->title('Loan application approved!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->button()
                    ->color('success')
                    ->visible(fn ($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                Action::make('Disapprove')
                    ->form([
                        Select::make('disapproval_reason_id')
                            ->relationship('disapproval_reason', 'name')
                            ->required(),
                        TextInput::make('remarks')
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'status' => LoanApplication::STATUS_DISAPPROVED,
                            'disapproval_date' => today(),
                            'remarks' => $data['remarks']
                        ]);
                        Notification::make()->title('Loan application disapproved!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->button()
                    ->color('danger')
                    ->visible(fn ($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record]), true),
            ])
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
            'index' => Pages\ListLoanApplications::route('/'),
            'create' => Pages\CreateLoanApplication::route('/create'),
            'edit' => Pages\EditLoanApplication::route('/{record}/edit'),
            'view' => Pages\ViewLoanApplication::route('/{record}'),
            'application-form' => Pages\LoanApplicationForm::route('/{loan_application}/application-form'),
        ];
    }
}
