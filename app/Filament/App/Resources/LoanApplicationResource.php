<?php

namespace App\Filament\App\Resources;

use App\Actions\LoanApplications\ApproveLoanApplication;
use App\Actions\LoanApplications\DisapproveLoanApplication;
use App\Actions\Loans\CreateNewLoan;
use App\Filament\App\Resources\LoanApplicationResource\Pages;
use App\Filament\App\Resources\LoanApplicationResource\Pages\CreditAndBackgroundInvestigationForm;
use App\Filament\App\Resources\LoanApplicationResource\Pages\CreditAndBackgroundInvestigationReport;
use App\Filament\App\Resources\LoanResource\Actions\ViewLoanDetailsActionGroup;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Account;
use App\Models\LoanApplication;
use App\Models\Member;
use App\Oxytoxin\DTO\Loan\LoanData;
use App\Oxytoxin\Providers\LoansProvider;
use App\Rules\BalancedBookkeepingEntries;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
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
                    ->required()
                    ->live(),
                TextInput::make('priority_number'),
                TextInput::make('desired_amount')->moneymask()->required(),
                TextInput::make('purpose'),
                TableRepeater::make('comakers')
                    ->schema([
                        Select::make('member_id')
                            ->label('Name')
                            ->options(Member::pluck('full_name', 'id'))
                            ->searchable()
                            ->required()
                            ->preload(),
                    ])
                    ->default([])
                    ->hideLabels(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('loan'))
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
                Action::make('CIBI')->label('CIBI')->button()->url(fn ($record) => route('filament.app.resources.loan-applications.credit-and-background-investigation-form', ['loan_application' => $record])),
                Action::make('Approve')
                    ->action(function (LoanApplication $record) {
                        app(ApproveLoanApplication::class)->handle($record, config('app.transaction_date'));
                        Notification::make()->title('Loan application approved!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->button()
                    ->color('success')
                    ->visible(fn ($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                Action::make('Disapprove')
                    ->form([
                        TextInput::make('priority_number')->default(fn ($record) => $record->priority_number),
                        Select::make('disapproval_reason_id')
                            ->relationship('disapproval_reason', 'name')
                            ->required(),
                        TextInput::make('remarks'),
                    ])
                    ->action(function (LoanApplication $record, $data) {
                        app(DisapproveLoanApplication::class)->handle(
                            loan_application: $record,
                            disapproval_reason_id: $data['disapproval_reason_id'],
                            priority_number: $data['priority_number'],
                            remarks: $data['remarks'],
                            disapproval_date: config('app.transaction_date')
                        );
                        Notification::make()->title('Loan application disapproved!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->button()
                    ->color('danger')
                    ->visible(fn ($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                Action::make('disclosure')
                    ->visible(fn ($record) => auth()->user()->can('manage loans') && !$record->loan && $record->status == LoanApplication::STATUS_APPROVED)
                    ->modalWidth(MaxWidth::ScreenExtraLarge)
                    ->fillForm(function ($record) {
                        $disclosure_sheet_items = LoansProvider::getDisclosureSheetItems($record->loan_type, $record->desired_amount, $record->member);

                        return [
                            'gross_amount' => $record->desired_amount,
                            'number_of_terms' => $record->number_of_terms,
                            'priority_number' => $record->priority_number,
                            'transaction_date' => config('app.transaction_date'),
                            'release_date' => config('app.transaction_date'),
                            'disclosure_sheet_items' => $disclosure_sheet_items,
                        ];
                    })
                    ->form(fn ($record) => [
                        TextInput::make('priority_number')->required(),
                        TextInput::make('gross_amount')->required()
                            ->readOnly()
                            ->moneymask(),
                        TextInput::make('number_of_terms')
                            ->readOnly(),
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('interest_rate')
                                    ->content(fn ($record) => str($record->loan_type?->interest_rate * 100 ?? 0)->append('%')->toString()),
                                Placeholder::make('interest')
                                    ->content(fn ($get) => format_money(LoansProvider::computeInterest($get('gross_amount') ?? 0, $record->loan_type, $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                                Placeholder::make('monthly_payment')
                                    ->content(fn ($get) => format_money(LoansProvider::computeMonthlyPayment($get('gross_amount') ?? 0, $record->loan_type, $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                            ]),
                        TableRepeater::make('disclosure_sheet_items')
                            ->hideLabels()
                            ->columnSpanFull()
                            ->reactive()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $items = collect($state);
                                $net_amount = $items->firstWhere('code', 'net_amount');
                                $items = $items->filter(fn ($i) => $i['code'] != 'net_amount');
                                $net_amount['credit'] = $items->sum('debit') - $items->sum('credit');
                                $items->push($net_amount);
                                $set('disclosure_sheet_items', $items->toArray());
                            })
                            ->columnWidths(['account_id' => '40%', 'member_id' => '13rem'])
                            ->rule(new BalancedBookkeepingEntries)
                            ->schema([
                                Select::make('member_id')
                                    ->options(Member::pluck('full_name', 'id'))
                                    ->label('Member')
                                    ->searchable()
                                    ->reactive()
                                    ->preload(),
                                Select::make('account_id')
                                    ->options(
                                        fn ($get) => Account::withCode()->whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->where('member_id', $get('member_id') ?? null)->pluck('code', 'id')
                                    )
                                    ->searchable()
                                    ->required()
                                    ->label('Account'),
                                TextInput::make('debit')
                                    ->moneymask(),
                                TextInput::make('credit')
                                    ->moneymask(),
                            ]),
                        DatePicker::make('release_date')->required()->native(false),
                    ])
                    ->action(function (LoanApplication $record, $data) {
                        $loanType = $record->loan_type;
                        $accounts = Account::withCode()->find(collect($data['disclosure_sheet_items'])->pluck('account_id'));
                        $items = collect($data['disclosure_sheet_items'])->map(function ($item) use ($accounts) {
                            $item['name'] = $accounts->find($item['account_id'])->code;
                            return $item;
                        })->toArray();
                        $loanData = new LoanData(
                            member_id: $record->member_id,
                            loan_application_id: $record->id,
                            loan_type_id: $loanType->id,
                            reference_number: $record->reference_number,
                            interest_rate: $loanType->interest_rate,
                            disclosure_sheet_items: $items,
                            priority_number: $data['priority_number'],
                            gross_amount: $data['gross_amount'],
                            number_of_terms: $data['number_of_terms'],
                            interest: LoansProvider::computeInterest(
                                amount: $data['gross_amount'],
                                loanType: $loanType,
                                number_of_terms: $data['number_of_terms'],
                            ),
                            monthly_payment: LoansProvider::computeMonthlyPayment(
                                amount: $data['gross_amount'],
                                loanType: $loanType,
                                number_of_terms: $data['number_of_terms'],
                            ),
                            release_date: config('app.transaction_date'),
                            transaction_date: config('app.transaction_date'),
                        );
                        app(CreateNewLoan::class)->handle($record, $loanData);
                        Notification::make()->title('New loan created.')->success()->send();
                    }),
                EditAction::make()
                    ->label('Dates')
                    ->form([
                        DatePicker::make('payment_start_date')->time(false)->native(false),
                        DatePicker::make('surcharge_start_date')->time(false)->native(false),
                    ]),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record]), true),
                ViewLoanDetailsActionGroup::getActions(),

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
            'credit-and-background-investigation-form' => CreditAndBackgroundInvestigationForm::route('/cibi-form/{loan_application}'),
            'credit-and-background-investigation-report' => CreditAndBackgroundInvestigationReport::route('/cibi-report/{cibi}')
        ];
    }
}
