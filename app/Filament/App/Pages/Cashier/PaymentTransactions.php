<?php

    namespace App\Filament\App\Pages\Cashier;

    use App\Actions\Savings\CreateNewSavingsAccount;
    use App\Enums\MsoType;
    use App\Enums\OtherPaymentTransactionExcludedAccounts;
    use App\Enums\PaymentTypes;
    use App\Filament\App\Pages\Cashier\Actions\CashierTransactionsPageCbuPayment;
    use App\Filament\App\Pages\Cashier\Actions\CashierTransactionsPageLoan;
    use App\Filament\App\Pages\Cashier\Actions\CashierTransactionsPageMSO;
    use App\Filament\App\Pages\Cashier\Actions\CashierTransactionsPageOthers;
    use App\Filament\App\Pages\Cashier\Actions\CashierTransactionsPageTimeDeposit;
    use App\Filament\App\Pages\Cashier\Traits\HasReceipt;
    use App\Filament\App\Resources\MemberResource;
    use App\Models\Account;
    use App\Models\CashCollectibleAccount;
    use App\Models\CashierTransaction;
    use App\Models\LoanAccount;
    use App\Models\Member;
    use App\Models\SavingsAccount;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use App\Oxytoxin\Providers\TimeDepositsProvider;
    use Filament\Actions\Action;
    use Filament\Actions\Concerns\InteractsWithActions;
    use Filament\Actions\Contracts\HasActions;
    use Filament\Forms\Components\Builder;
    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\Placeholder;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Notifications\Notification;
    use Filament\Schemas\Components\Actions;
    use Filament\Schemas\Components\Flex;
    use Filament\Schemas\Components\Section;
    use Filament\Schemas\Schema;
    use Filament\Support\Colors\Color;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Number;
    use Livewire\Component;

    class PaymentTransactions extends Component implements HasActions, HasForms
    {
        use HasReceipt, InteractsWithActions, InteractsWithForms, RequiresBookkeeperTransactionDate;

        public $data = [];

        private $transactions = [];

        public function form(Schema $schema): Schema
        {
            return $schema
                ->statePath('data')
                ->components([
                    Section::make('Quick Account Lookup')
                        ->compact()
                        ->schema([
                            Flex::make([
                                Select::make('lookup_account_number')
                                    ->hiddenLabel()
                                    ->searchable(['number', 'name'])
                                    ->options(
                                        Account::whereNotNull('member_id')
                                            ->whereIn('tag', ['regular_savings', 'imprest_savings', 'love_gift_savings', 'member_time_deposits', 'member_loans_receivable'])
                                            ->get()
                                            ->mapWithKeys(fn($account) => [$account->number => "{$account->number} - {$account->name}"])
                                    ),
                                Actions::make([
                                    Action::make('lookupAccount')
                                        ->label('Lookup')
                                        ->color(Color::Blue)
                                        ->action(function () {
                                            $accountNumber = $this->data['lookup_account_number'] ?? null;
                                            if ($accountNumber) {
                                                $this->performAccountLookup($accountNumber);
                                            }
                                        }),
                                ])->grow(false),
                            ]),
                        ]),
                    Placeholder::make('transaction_date')
                        ->content($this->transaction_date?->format('m/d/Y')),
                    Select::make('member_id')
                        ->label('Member')
                        ->options(Member::pluck('full_name', 'id'))
                        ->searchable()
                        ->afterStateUpdated(fn($set) => $set('transactions', []))
                        ->reactive(),
                    Placeholder::make('member_type')
                        ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
                    Builder::make('transactions')
                        ->required()
                        ->collapsible()
                        ->addBetweenAction(
                            fn(Action $action) => $action->visible(false),
                        )
                        ->blocks([
                            Block::make('cbu')
                                ->label('CBU')
                                ->columns(2)
                                ->visible(fn($get) => $get('../member_id'))
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'cbu'])
                                        ->schema([
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('reference_number')->required()
                                                ->unique('capital_subscription_payments'),
                                            TextInput::make('amount')
                                                ->required()
                                                ->moneymask(),
                                        ]),
                                ]),
                            Block::make('others')
                                ->label('Others')
                                ->columns(2)
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'others'])
                                        ->schema([
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('payee')->required()->default(fn($get) => Member::find($get('../../../member_id'))?->full_name),
                                            Select::make('account_id')
                                                ->options(
                                                    fn($get) => Account::withCode()->whereDoesntHave('children', fn($q) => $q->whereNull('member_id'))
                                                        ->whereDoesntHave('ancestorsAndSelf', fn($q) => $q->whereIn('id', OtherPaymentTransactionExcludedAccounts::get()))
                                                        ->where('member_id', $get('member_accounts') ? $get('../../../member_id') : null)
                                                        ->pluck('code', 'id')
                                                )
                                                ->searchable()
                                                ->required()
                                                ->label('Account'),
                                            TextInput::make('reference_number')->required()
                                                ->unique('capital_subscription_payments'),
                                            TextInput::make('amount')
                                                ->required()
                                                ->moneymask(),
                                        ]),
                                ]),
                            Block::make('savings')
                                ->visible(fn($get) => $get('../member_id'))
                                ->columns(2)
                                ->schema(fn() => [
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'savings'])
                                        ->schema([
                                            Select::make('savings_account_id')
                                                ->options(fn($get) => SavingsAccount::whereMemberId($get('../../../member_id'))->pluck('number', 'id'))
                                                ->label('Account')
                                                ->required()
                                                ->suffixAction(
                                                    fn($get) => Action::make('NewAccount')
                                                        ->label('New Account')
                                                        ->modalHeading('New Savings Account')
                                                        ->schema([
                                                            TextInput::make('name')
                                                                ->required(),
                                                        ])
                                                        ->visible(fn($get) => $get('../../../member_id'))
                                                        ->action(function ($data, $get) {
                                                            app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                                                                member_id: $get('../../../member_id'),
                                                                name: $data['name'],
                                                            ));
                                                            Notification::make()->title('Savings account created!')->success()->send();
                                                        })
                                                        ->icon('heroicon-m-plus')
                                                        ->color(Color::Emerald),
                                                ),
                                            Select::make('action')
                                                ->options([
                                                    '-1' => 'Withdraw',
                                                    '1' => 'Deposit',
                                                ])
                                                ->live()
                                                ->default('1')
                                                ->required(),
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('amount')
                                                ->required()
                                                ->moneymask(),
                                        ]),
                                ]),
                            Block::make('imprest')
                                ->columns(2)
                                ->visible(fn($get) => $get('../member_id'))
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'imprest'])
                                        ->schema([
                                            Select::make('action')
                                                ->options([
                                                    '-1' => 'Withdraw',
                                                    '1' => 'Deposit',
                                                ])
                                                ->live()
                                                ->default('1')
                                                ->required(),
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('amount')
                                                ->required()
                                                ->moneymask(),
                                        ]),
                                ]),
                            Block::make('love_gift')
                                ->columns(2)
                                ->visible(fn($get) => $get('../member_id'))
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'love-gifts'])
                                        ->schema([
                                            Select::make('action')
                                                ->options([
                                                    '-1' => 'Withdraw',
                                                    '1' => 'Deposit',
                                                ])
                                                ->live()
                                                ->default('1')
                                                ->required(),
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('amount')
                                                ->required()
                                                ->moneymask(),
                                        ]),
                                ]),
                            Block::make('time_deposit')
                                ->columns(2)
                                ->visible(fn($get) => $get('../member_id'))
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'time-deposit'])
                                        ->schema([
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('reference_number')->required()
                                                ->unique('time_deposits'),
                                            TextInput::make('amount')
                                                ->required()
                                                ->moneymask()
                                                ->minValue(TimeDepositsProvider::MINIMUM_DEPOSIT)->default(TimeDepositsProvider::MINIMUM_DEPOSIT),
                                            TextInput::make('interest_rate')
                                                ->required()
                                                ->default(3)
                                                ->reactive()
                                                ->minValue(0.1),
                                            TextInput::make('number_of_days')
                                                ->default(TimeDepositsProvider::NUMBER_OF_DAYS)
                                                ->reactive()
                                                ->minValue(1),
                                            Placeholder::make('maturity_date')->content(fn($get) => TimeDepositsProvider::getMaturityDate($this->transaction_date, $get('number_of_days'))->format('F d, Y')),
                                            Placeholder::make('maturity_amount')->content(fn($get) => Number::currency(TimeDepositsProvider::getMaturityAmount(floatval($get('amount')), floatval($get('interest_rate')) / 100, $get('number_of_days')), 'PHP')),
                                        ]),
                                ]),
                            Block::make('cash_collection')
                                ->columns(2)
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'cash-collection'])
                                        ->schema([
                                            Select::make('cash_collectible_account_id')
                                                ->options(CashCollectibleAccount::pluck('name', 'id'))
                                                ->label('Cash Collectible')
                                                ->required(),
                                            TextInput::make('payee')
                                                ->required(),
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('reference_number')->required()
                                                ->unique('cash_collectible_payments'),
                                            TextInput::make('amount')->required()->moneymask(),
                                        ]),
                                ]),
                            Block::make('loan')
                                ->columns(2)
                                ->visible(fn($get) => $get('../member_id'))
                                ->schema([
                                    Section::make('')
                                        ->extraAttributes(['data-transaction' => 'loan'])
                                        ->schema([
                                            Select::make('loan_account_id')
                                                ->label('Loan Account')
                                                ->options(
                                                    fn($get) => Account::query()
                                                        ->join('loans', 'accounts.id', '=', 'loans.loan_account_id')
                                                        ->whereNotNull('accounts.member_id')
                                                        ->whereTag('member_loans_receivable')
                                                        ->where('accounts.member_id', $get('../../../member_id'))
                                                        ->where('loans.posted', true)
                                                        ->where('loans.outstanding_balance', '>', 0)
                                                        ->selectRaw("accounts.id, CONCAT(accounts.number, ' > ', loans.reference_number) as identifier")
                                                        ->pluck('identifier', 'id')
                                                )
                                                ->searchable()
                                                ->live()
                                                ->afterStateUpdated(fn($set, $state) => $set('amount', round(LoanAccount::find($state)?->loan?->monthly_payment, 2)))
                                                ->required()
                                                ->preload(),
                                            Placeholder::make('loan_type')
                                                ->content(fn($get) => LoanAccount::find($get('loan_account_id'))?->loan?->loan_type?->name),
                                            Placeholder::make('outstanding_balance')
                                                ->content(fn($get) => round(LoanAccount::find($get('loan_account_id'))?->loan?->outstanding_balance, 2)),
                                            Select::make('payment_type_id')
                                                ->paymenttype()
                                                ->required(),
                                            TextInput::make('reference_number')->required()
                                                ->unique('loan_payments'),
                                            TextInput::make('amount')->required()->moneymask(),
                                            TextInput::make('remarks'),
                                        ]),
                                ]),
                        ]),
                    Placeholder::make('transaction-selector')
                        ->label(false)
                        ->content(fn($get) => view('filament.app.pages.cashier.transaction-selector', ['member' => $get('member_id')])),
                    Actions::make([
                        Action::make('submit')
                            ->action(function () {
                                DB::beginTransaction();
                                $formData = $this->form->getState();
                                $member = Member::find($formData['member_id']);
                                $transactions = [];
                                $mso_links = [];
                                $transaction_type = TransactionType::CRJ();

                                foreach ($formData['transactions'] as $key => $transaction) {
                                    $data = new TransactionData(
                                        account_id: $transaction['data']['account_id'] ?? 0,
                                        transactionType: $transaction_type,
                                        reference_number: $transaction['data']['reference_number'] ?? '',
                                        payment_type_id: $transaction['data']['payment_type_id'],
                                        member_id: $member?->id,
                                        transaction_date: $this->transaction_date,
                                        payee: $formData['payee'] ?? $member?->full_name ?? 'SKSU-MPC',
                                    );
                                    if ($data->payment_type_id == PaymentTypes::DEPOSIT_SLIP->value) {
                                        $data->reference_number = str('SLIP')->append((config('app.transaction_date') ?? today())->format('Y').'-')->append(str_pad(random_int(1, 100000), 6, '0', STR_PAD_LEFT));
                                    }

                                    if ($transaction['type'] == 'cbu') {
                                        $data->account_id = $member->capital_subscription_account->id;
                                        $data->credit = $transaction['data']['amount'];
                                        $transactions[] = app(CashierTransactionsPageCbuPayment::class)->handle($data);
                                    }
                                    if ($transaction['type'] == 'others') {
                                        $data->account_id = $transaction['data']['account_id'];
                                        $data->payee = $transaction['data']['payee'];
                                        $data->credit = $transaction['data']['amount'];
                                        $transactions[] = app(CashierTransactionsPageOthers::class)->handle($data);
                                    }
                                    if (in_array($transaction['type'], ['savings', 'imprest', 'love_gift'])) {
                                        $is_deposit = $transaction['data']['action'] == 1;
                                        $mso_type = match ($transaction['type']) {
                                            'savings' => MsoType::SAVINGS,
                                            'imprest' => MsoType::IMPREST,
                                            'love_gift' => MsoType::LOVE_GIFT,
                                        };
                                        $data->account_id = match ($transaction['type']) {
                                            'savings' => $transaction['data']['savings_account_id'],
                                            'imprest' => $member->imprest_account->id,
                                            'love_gift' => $member->love_gift_account->id,
                                        };
                                        if ($is_deposit) {
                                            $data->credit = $transaction['data']['amount'];
                                        } else {
                                            $data->debit = $transaction['data']['amount'];
                                        }
                                        $transactions[] = app(CashierTransactionsPageMSO::class)->handle(
                                            msoType: $mso_type,
                                            is_deposit: $is_deposit,
                                            data: $data
                                        );
                                        $mso_links[] = match ($transaction['type']) {
                                            'savings' => [
                                                'type' => 'Savings',
                                                'url' => MemberResource::getUrl('savings-subsidiary-ledger', ['savings_account' => $transaction['data']['savings_account_id']]),
                                            ],
                                            'imprest' => [
                                                'type' => 'Imprest',
                                                'url' => MemberResource::getUrl('imprest-subsidiary-ledger', ['member' => $member->id]),
                                            ],
                                            'love_gift' => [
                                                'type' => 'Love Gift',
                                                'url' => MemberResource::getUrl('love-gifts-subsidiary-ledger', ['member' => $member->id]),
                                            ],
                                        };
                                    }

                                    if ($transaction['type'] == 'time_deposit') {
                                        $data->credit = $transaction['data']['amount'];
                                        $td_result = app(CashierTransactionsPageTimeDeposit::class)->handle($data, $transaction['data']['interest_rate'] / 100, $transaction['data']['number_of_days']);
                                        $transactions[] = $td_result;
                                        $mso_links[] = [
                                            'type' => 'Time Deposit',
                                            'url' => MemberResource::getUrl('time-deposit-subsidiary-ledger', ['time_deposit_account' => $td_result['time_deposit_account_id']]),
                                        ];
                                    }

                                    if ($transaction['type'] == 'loan') {
                                        $data->account_id = $transaction['data']['loan_account_id'];
                                        $data->credit = $transaction['data']['amount'];
                                        $data->remarks = $transaction['data']['remarks'];
                                        $transactions[] = app(CashierTransactionsPageLoan::class)->handle($data);
                                    }
                                }
                                $this->transactions = $transactions;
                                DB::commit();
                                CashierTransaction::create([
                                    'user_id' => auth()->id(),
                                    'member_id' => $member?->id,
                                    'transaction_date' => $this->transaction_date,
                                    'transactions' => $transactions,
                                ]);
                                Notification::make()->title('Transactions successful!')->success()->send();
                                $this->form->fill();
                                $this->replaceMountedAction('receipt', ['transactions' => $transactions, 'mso_links' => $mso_links]);
                            })
                            ->modalContent(function ($action) {
                                return view('filament.app.pages.cashier.transaction-summary', ['data' => $this->data]);
                            })
                            ->requiresConfirmation(),
                    ]),
                ]);
        }

        public function addTransaction($block)
        {
            $this->mountAction('add', ['block' => $block], ['schemaComponent' => 'form.transactions']);
        }

        public function performAccountLookup(string $accountNumber): void
        {
            if (blank($accountNumber)) {
                return;
            }

            $account = Account::where('number', trim($accountNumber))
                ->whereNotNull('member_id')
                ->first();
            if (!$account) {
                Notification::make()
                    ->title('Account not found')
                    ->body('No account found with number: '.$accountNumber)
                    ->warning()
                    ->send();

                return;
            }

            $tagToBlockMap = [
                'regular_savings' => 'savings',
                'imprest_savings' => 'imprest',
                'love_gift_savings' => 'love_gift',
                'member_time_deposits' => 'time_deposit',
                'member_loans_receivable' => 'loan',
            ];

            $blockType = $tagToBlockMap[$account->tag] ?? null;

            if (!$blockType) {
                Notification::make()
                    ->title('Unsupported account type')
                    ->warning()
                    ->send();

                return;
            }

            $member = $account->member;
            $blockData = [];

            if ($account->tag === 'regular_savings') {
                $blockData['savings_account_id'] = $account->id;
                $blockData['action'] = '1';
            }

            if ($account->tag === 'member_loans_receivable') {
                $blockData['loan_account_id'] = $account->id;
            }

            $previousMemberId = (int) $this->data['member_id'] ?? null;

            $this->data['member_id'] = $member->id;

            if ($previousMemberId && $previousMemberId !== $member->id) {
                $this->data['transactions'] = [];
            } elseif (!isset($this->data['transactions'])) {
                $this->data['transactions'] = [];
            }

            $this->data['transactions'][] = [
                'type' => $blockType,
                'data' => $blockData,
            ];

            $this->form->fill($this->data);

            $typeLabel = ucfirst(str_replace('_', ' ', $blockType));

            Notification::make()
                ->title('Account found')
                ->body("{$typeLabel} account for {$member->full_name} has been loaded.")
                ->success()
                ->send();
        }

        public function mount()
        {
            $this->form->fill();
        }

        public function render()
        {
            return view('filament.app.pages.cashier.payment-transactions');
        }
    }
