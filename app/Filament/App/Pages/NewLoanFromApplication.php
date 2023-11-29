<?php

namespace App\Filament\App\Pages;

use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Loan;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\LoanApplication;
use App\Oxytoxin\LoansProvider;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

use function Filament\Support\format_money;

class NewLoanFromApplication extends Page implements HasTable
{
    use InteractsWithTable, HasViewLoanDetailsActionGroup;

    protected static string $view = 'filament.app.pages.new-loan-from-application';

    protected static ?string $navigationLabel = 'Approved Loan Applications';

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanApplication::with('loan')->whereStatus(LoanApplication::STATUS_APPROVED))
            ->columns([
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('transaction_date')->date('m/d/Y')->label('Date Applied'),
                TextColumn::make('loan_type.name'),
                TextColumn::make('desired_amount')->money('PHP'),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($record) => $record->loan ? ($record->loan->posted ? 'POSTED' : 'PENDING') : 'APPROVED')
                    ->color(fn ($record) => $record->loan ? ($record->loan->posted ? 'success' : 'warning') : 'success')
                    ->badge(),
            ])
            ->defaultLoanApplicationFilters()
            ->actions([
                Action::make('new_loan')
                    ->visible(fn ($record) => auth()->user()->can('manage loans') && !$record->loan)
                    ->fillForm(function ($record) {
                        $deductions = LoansProvider::computeDeductions($record->loan_type, $record->desired_amount, $record->member);
                        return [
                            'gross_amount' => $record->desired_amount,
                            'number_of_terms' => $record->number_of_terms,
                            'priority_number' => $record->priority_number,
                            'transaction_date' => today(),
                            'release_date' => today(),
                            'deductions' => $deductions
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
                            'priority_number' => $data['priority_number']
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
                        $this->dispatch('refresh');
                        Notification::make()->title('New loan created.')->success()->send();
                    }),
                $this->getViewLoanApplicationLoanDetailsActionGroup()
            ]);
    }
}
