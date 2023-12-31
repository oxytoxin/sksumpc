<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\LoanApplicationResource\Pages;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Oxytoxin\LoansProvider;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use function Filament\Support\format_money;

class LoanApplicationResource extends Resource
{
    protected static ?string $model = LoanApplication::class;

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 1;

    use HasViewLoanDetailsActionGroup;

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

                ]),
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
                TextInput::make('purpose'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('priority_number')->searchable(),
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
                        TextInput::make('priority_number'),
                        Select::make('disapproval_reason_id')
                            ->relationship('disapproval_reason', 'name')
                            ->required(),
                        TextInput::make('remarks'),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'priority_number' => $data['priority_number'],
                            'status' => LoanApplication::STATUS_DISAPPROVED,
                            'disapproval_date' => today(),
                            'remarks' => $data['remarks'],
                        ]);
                        Notification::make()->title('Loan application disapproved!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->button()
                    ->color('danger')
                    ->visible(fn ($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                Action::make('new_loan')
                    ->visible(fn ($record) => auth()->user()->can('manage loans') && !$record->loan && $record->status == LoanApplication::STATUS_APPROVED)
                    ->fillForm(function ($record) {
                        $deductions = LoansProvider::computeDeductions($record->loan_type, $record->desired_amount, $record->member);
                        return [
                            'gross_amount' => $record->desired_amount,
                            'number_of_terms' => $record->number_of_terms,
                            'priority_number' => $record->priority_number,
                            'transaction_date' => today(),
                            'release_date' => today(),
                            'deductions' => $deductions,
                        ];
                    })
                    ->form(fn ($record) => [
                        TextInput::make('priority_number')->required(),
                        DatePicker::make('transaction_date')->required()->native(false),
                        TextInput::make('gross_amount')->required()
                            ->readOnly()
                            ->moneymask(),
                        TextInput::make('number_of_terms')
                            ->readOnly(),
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('interest_rate')
                                    ->content(fn ($get) => str($record->loan_type?->interest_rate * 100 ?? 0)->append('%')->toString()),
                                Placeholder::make('interest')
                                    ->content(fn ($get) => format_money(LoansProvider::computeInterest($get('gross_amount') ?? 0, $record->loan_type, $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                                Placeholder::make('monthly_payment')
                                    ->content(fn ($get) => format_money(LoansProvider::computeMonthlyPayment($get('gross_amount') ?? 0, $record->loan_type, $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                            ]),
                        TableRepeater::make('deductions')
                            ->schema([
                                TextInput::make('name')->readOnly(fn ($get) => boolval($get('readonly'))),
                                TextInput::make('amount')
                                    ->moneymask()
                                    ->readOnly(fn ($get) => boolval($get('readonly'))),
                                Hidden::make('readonly')->default(false),
                            ])
                            ->live(true)
                            ->orderColumn(false)
                            ->hideLabels(),
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('deductions_amount')
                                    ->content(fn ($get) => format_money(collect($get('deductions'))->sum('amount'), 'PHP')),
                                Placeholder::make('net_amount')
                                    ->content(fn ($get) => format_money(floatval(str_replace(',', '', $get('gross_amount') ?? 0)) - collect($get('deductions'))->sum('amount'), 'PHP')),
                            ]),
                        DatePicker::make('release_date')->required()->native(false),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'priority_number' => $data['priority_number'],
                        ]);
                        $loanType = $record->loan_type;
                        Loan::create([
                            ...$data,
                            'loan_application_id' => $record->id,
                            'reference_number' => $record->reference_number,
                            'loan_type_id' => $loanType->id,
                            'interest_rate' => $loanType->interest_rate,
                            'interest' => LoansProvider::computeInterest($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                            'member_id' => $record->member_id,
                            'monthly_payment' => LoansProvider::computeMonthlyPayment($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                        ]);
                        Notification::make()->title('New loan created.')->success()->send();
                    }),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record]), true),
                self::getViewLoanApplicationLoanDetailsActionGroup(),

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
            'index' => Pages\ManageLoanApplications::route('/'),
            'view' => Pages\ViewLoanApplication::route('/{record}'),
            'application-form' => Pages\LoanApplicationForm::route('/{loan_application}/application-form'),
        ];
    }
}
