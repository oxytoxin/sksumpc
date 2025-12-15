<?php

    namespace App\Filament\App\Resources;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Schemas\Components\Section;
    use Filament\Actions\EditAction;
    use Filament\Actions\Action;
    use Filament\Support\Enums\Width;
    use Filament\Schemas\Components\Grid;
    use App\Filament\App\Resources\LoanApplicationResource\Pages\ManageLoanApplications;
    use App\Filament\App\Resources\LoanApplicationResource\Pages\ViewLoanApplication;
    use App\Filament\App\Resources\LoanApplicationResource\Pages\LoanApplicationForm;
    use App\Filament\App\Resources\LoanApplicationResource\Pages\CoborrowerUndertaking;
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
    use Auth;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\Placeholder;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Infolists\Components\TextEntry;
    use Filament\Notifications\Notification;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Table;

    use function Filament\Support\format_money;

    class LoanApplicationResource extends Resource
    {
        protected static ?string $model = LoanApplication::class;

        protected static string|\UnitEnum|null $navigationGroup = 'Loan';

        protected static ?int $navigationSort = 1;

        use HasViewLoanDetailsActionGroup;

        public static function shouldRegisterNavigation(): bool
        {
            return Auth::user()->can('manage loans');
        }

        public static function infolist(Schema $schema): Schema
        {
            return $schema->components(fn($record) => [
                TextEntry::make('reference_number'),
                TextEntry::make('member.full_name'),
                TextEntry::make('loan_type.name'),
                TextEntry::make('number_of_terms'),
                TextEntry::make('priority_number'),
                TextEntry::make('desired_amount')->money('PHP'),
                TextEntry::make('transaction_date')->date('m/d/Y'),
                TextEntry::make('purpose'),
                Section::make('Loan Details')
                    ->visible(fn($record) => $record->loan)
                    ->schema([
                        TextEntry::make('loan.gross_amount')->money('PHP')->label('Gross Amount'),
                        TextEntry::make('loan.deductions_amount')->money('PHP')->label('Deductions Amount'),
                        TextEntry::make('loan.net_amount')->money('PHP')->label('Net Amount'),
                        TextEntry::make('loan.interest_rate')->formatStateUsing(fn($state) => str($state * 100)->append('%'))->label('Interest Rate'),
                        TextEntry::make('loan.interest')->money('PHP')->label('Interest Amount'),
                        TextEntry::make('loan.monthly_payment')->money('PHP')->label('Monthly Payment'),
                        TextEntry::make('loan.release_date')->date('m/d/Y')->label('Release Date'),
                    ]),
            ])->columns(1);
        }

        public static function form(Schema $schema): Schema
        {
            return $schema
                ->components([
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
                    Repeater::make('comakers')
                        ->schema([
                            Select::make('member_id')
                                ->label('Name')
                                ->searchable()
                                ->required()
                                ->relationship('member', 'full_name')
                                ->preload(),
                        ])
                        ->columnSpanFull()
                        ->maxItems(2)
                        ->default([])
                        ->relationship()
                        ->table([
                            Repeater\TableColumn::make('Name'),
                        ]),
                ]);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->modifyQueryUsing(fn($query) => $query->with('loan'))
                ->defaultSort('transaction_date', 'desc')
                ->columns([
                    TextColumn::make('member.full_name')->searchable(),
                    TextColumn::make('priority_number')->searchable(),
                    TextColumn::make('transaction_date')->date('m/d/Y')->label('Date Applied')->sortable(),
                    TextColumn::make('loan_type.name'),
                    TextColumn::make('desired_amount')->money('PHP'),
                    TextColumn::make('status')
                        ->formatStateUsing(fn($state) => match ($state) {
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
                ->defaultLoanApplicationFilters(type: 0)
                ->actions([
                    EditAction::make()
                        ->visible(fn($record) => Auth::user()->can('manage loans') && $record->status != LoanApplication::STATUS_POSTED),
                    Action::make('CIBI')->label('CIBI')->button()->url(fn($record) => route('filament.app.resources.loan-applications.credit-and-background-investigation-form', ['loan_application' => $record])),
                    Action::make('Approve')
                        ->action(function (LoanApplication $record) {
                            app(ApproveLoanApplication::class)->handle($record, config('app.transaction_date'));
                            Notification::make()->title('Loan application approved!')->success()->send();
                        })
                        ->requiresConfirmation()
                        ->button()
                        ->color('success')
                        ->visible(fn($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                    Action::make('Disapprove')
                        ->schema([
                            TextInput::make('priority_number')->default(fn($record) => $record->priority_number),
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
                        ->visible(fn($record) => $record->status == LoanApplication::STATUS_PROCESSING),
                    Action::make('disclosure')
                        ->visible(fn($record) => Auth::user()->can('manage loans') && !$record->loan && $record->status == LoanApplication::STATUS_APPROVED)
                        ->modalWidth(Width::ScreenExtraLarge)
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
                        ->schema(fn($record) => [
                            TextInput::make('priority_number')->required(),
                            TextInput::make('gross_amount')->required()
                                ->readOnly()
                                ->moneymask(),
                            TextInput::make('number_of_terms')
                                ->readOnly(),
                            Grid::make(3)
                                ->schema([
                                    Placeholder::make('interest_rate')
                                        ->content(fn($record) => str($record->loan_type?->interest_rate * 100 ?? 0)->append('%')->toString()),
                                    Placeholder::make('interest')
                                        ->content(fn($get) => format_money(LoansProvider::computeInterest($get('gross_amount') ?? 0, $record->loan_type, $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                                    Placeholder::make('monthly_payment')
                                        ->content(fn($get) => format_money(LoansProvider::computeMonthlyPayment($get('gross_amount') ?? 0, $record->loan_type, $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                                ]),
                            Repeater::make('disclosure_sheet_items')
                                ->columnSpanFull()
                                ->reactive()
                                ->reorderable(false)
                                ->afterStateUpdated(function ($set, $state) {
                                    $items = collect($state);
                                    $net_amount = $items->firstWhere('code', 'net_amount');
                                    $items = $items->filter(fn($i) => ($i['code'] ?? '') != 'net_amount');
                                    $net_amount['credit'] = round($items->sum(fn($item) => (float) $item['debit']) - $items->sum(fn($item) => (float) $item['credit']), 4);
                                    $items->push($net_amount);
                                    $set('disclosure_sheet_items', $items->toArray());
                                })
                                ->table([
                                    Repeater\TableColumn::make('Member')->width('15rem'),
                                    Repeater\TableColumn::make('Account')->width('40%'),
                                    Repeater\TableColumn::make('Debit'),
                                    Repeater\TableColumn::make('Credit'),
                                ])
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
                                            fn($get) => Account::withCode()->pluck('code', 'id')
                                        )
                                        ->searchable()
                                        ->required()
                                        ->label('Account'),
                                    TextInput::make('debit')
                                        ->formatStateUsing(fn($state) => $state ? round($state, 4) : '')
                                        ->moneymask(),
                                    TextInput::make('credit')
                                        ->formatStateUsing(fn($state) => $state ? round($state, 4) : '')
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
                                priority_number: $data['priority_number'],
                                gross_amount: $data['gross_amount'],
                                number_of_terms: $data['number_of_terms'],
                                interest_rate: $loanType->interest_rate,
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
                                disclosure_sheet_items: $items,
                            );
                            app(CreateNewLoan::class)->handle($record, $loanData);
                            Notification::make()->title('New loan created.')->success()->send();
                        }),
                    EditAction::make('dates')
                        ->label('Dates')
                        ->schema([
                            DatePicker::make('payment_start_date')->time(false)->native(false),
                            DatePicker::make('surcharge_start_date')->time(false)->native(false),
                        ]),
                    Action::make('print')
                        ->icon('heroicon-o-printer')
                        ->url(fn($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record]), true),
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
                'index' => ManageLoanApplications::route('/'),
                'view' => ViewLoanApplication::route('/{record}'),
                'application-form' => LoanApplicationForm::route('/{loan_application}/application-form'),
                'coborrowers_undertaking' => CoborrowerUndertaking::route('/{loan_application}/coborrowers-undertaking'),
                'credit-and-background-investigation-form' => CreditAndBackgroundInvestigationForm::route('/cibi-form/{loan_application}'),
                'credit-and-background-investigation-report' => CreditAndBackgroundInvestigationReport::route('/cibi-report/{cibi}'),
            ];
        }
    }
