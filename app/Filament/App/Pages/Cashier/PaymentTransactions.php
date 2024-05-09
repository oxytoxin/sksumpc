<?php

namespace App\Filament\App\Pages\Cashier;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\CashCollections\PayCashCollectible;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\Loans\PayLoan;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Models\CashCollectible;
use App\Models\LoanAccount;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\SavingsAccount;
use App\Models\SystemConfiguration;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use function Filament\Support\format_money;

class PaymentTransactions extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms, RequiresBookkeeperTransactionDate;

    public $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Placeholder::make('transaction_date')
                    ->content(date_create($this->transaction_date)->format('m/d/Y')),
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->afterStateUpdated(fn ($set) => $set('transactions', []))
                    ->required()
                    ->reactive(),
                Placeholder::make('member_type')
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
                Builder::make('transactions')
                    ->required()
                    ->disabled(fn ($get) => !$get('member_id'))
                    ->collapsible()
                    ->addBetweenAction(
                        fn (Action $action) => $action->visible(false),
                    )
                    ->blocks([
                        Block::make('savings')
                            ->columns(2)
                            ->schema(fn () => [
                                Section::make('')
                                    ->extraAttributes(['data-transaction' => 'savings'])
                                    ->schema([
                                        Select::make('savings_account_id')
                                            ->options(fn ($get) => SavingsAccount::whereMemberId($get('../../../member_id'))->pluck('number', 'id'))
                                            ->label('Account')
                                            ->required()
                                            ->suffixAction(
                                                fn ($get) => Action::make('NewAccount')
                                                    ->label('New Account')
                                                    ->modalHeading('New Savings Account')
                                                    ->form([
                                                        TextInput::make('name')
                                                            ->required(),
                                                    ])
                                                    ->visible(fn ($get) => $get('../../../member_id'))
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
                                        TextInput::make('reference_number')->required()
                                            ->visible(fn ($get) => $get('action') == '1')
                                            ->unique('savings'),
                                        TextInput::make('amount')
                                            ->required()
                                            ->moneymask(),
                                    ]),
                            ]),
                        Block::make('imprest')
                            ->columns(2)
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
                                        TextInput::make('reference_number')->required()
                                            ->visible(fn ($get) => $get('action') == '1')
                                            ->unique('imprests'),
                                        TextInput::make('amount')
                                            ->required()
                                            ->moneymask(),
                                    ]),
                            ]),
                        Block::make('love_gift')
                            ->columns(2)
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
                                        TextInput::make('reference_number')->required()
                                            ->visible(fn ($get) => $get('action') == '1')
                                            ->unique('love_gifts'),
                                        TextInput::make('amount')
                                            ->required()
                                            ->moneymask(),
                                    ]),
                            ]),
                        Block::make('time_deposit')
                            ->columns(2)
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
                                            ->afterStateUpdated(fn ($set, $state) => $set('maturity_amount', TimeDepositsProvider::getMaturityAmount(floatval($state))))
                                            ->minValue(TimeDepositsProvider::MINIMUM_DEPOSIT)->default(TimeDepositsProvider::MINIMUM_DEPOSIT),
                                        Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                                        Placeholder::make('maturity_date')->content(TimeDepositsProvider::getMaturityDate(today())->format('F d, Y')),
                                        Placeholder::make('maturity_amount')->content(fn ($get) => format_money(TimeDepositsProvider::getMaturityAmount(floatval($get('amount'))), 'PHP')),
                                    ]),
                            ]),
                        Block::make('cash_collection')
                            ->columns(2)
                            ->schema([
                                Section::make('')
                                    ->extraAttributes(['data-transaction' => 'cash-collection'])
                                    ->schema([
                                        Select::make('cash_collectible_id')
                                            ->options(CashCollectible::pluck('name', 'id'))
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
                            ->schema([
                                Section::make('')
                                    ->extraAttributes(['data-transaction' => 'loan'])
                                    ->schema([
                                        Select::make('loan_account_id')
                                            ->label('Loan Account')
                                            ->options(fn ($get) => LoanAccount::whereMemberId($get('../../../member_id'))->whereHas('loan', fn ($q) => $q->where('posted', true)->where('outstanding_balance', '>', 0))->pluck('number', 'id'))
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(fn ($set, $state) => $set('amount', LoanAccount::find($state)?->loan?->monthly_payment))
                                            ->required()
                                            ->preload(),
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
                Actions::make([
                    Action::make('submit')
                        ->action(function () {
                            DB::beginTransaction();
                            $formData = $this->form->getState();
                            $member = Member::find($formData['member_id']);
                            $transactions = [];
                            $payment_types = PaymentType::get();
                            foreach ($formData['transactions'] as $key => $transaction) {
                                if ($transaction['type'] == 'cbu') {
                                    app(PayCapitalSubscription::class)->handle($member->capital_subscriptions_common, new CapitalSubscriptionPaymentData(
                                        payment_type_id: $transaction['data']['payment_type_id'],
                                        reference_number: $transaction['data']['reference_number'],
                                        amount: $transaction['data']['amount'],
                                        transaction_date: $this->transaction_date,
                                    ), TransactionType::firstWhere('name', 'CRJ'));
                                    $transactions[] = [
                                        'account_number' => $member->capital_subscription_account->number,
                                        'account_name' => $member->capital_subscription_account->name,
                                        'reference_number' => $transaction['data']['reference_number'],
                                        'amount' => $transaction['data']['amount'],
                                        'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                        'remarks' => 'CBU PAYMENT'
                                    ];
                                }
                                if ($transaction['type'] == 'savings') {
                                    $isDeposit = $transaction['data']['action'] == 1;
                                    if ($isDeposit) {
                                        app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                                            payment_type_id: $transaction['data']['payment_type_id'],
                                            reference_number: $transaction['data']['reference_number'],
                                            amount: $transaction['data']['amount'],
                                            savings_account_id: $transaction['data']['savings_account_id'],
                                            transaction_date: $this->transaction_date,
                                        ), TransactionType::firstWhere('name', 'CRJ'));
                                        $savings_account = SavingsAccount::find($transaction['data']['savings_account_id']);
                                        $transactions[] = [
                                            'account_number' => $savings_account->number,
                                            'account_name' => $savings_account->name,
                                            'reference_number' => $transaction['data']['reference_number'],
                                            'amount' => $transaction['data']['amount'],
                                            'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                            'remarks' => 'SAVINGS DEPOSIT'
                                        ];
                                    } else {
                                        app(WithdrawFromSavingsAccount::class)->handle($member, new SavingsData(
                                            payment_type_id: $transaction['data']['payment_type_id'],
                                            reference_number: SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
                                            amount: $transaction['data']['amount'],
                                            savings_account_id: $transaction['data']['savings_account_id'],
                                            transaction_date: $this->transaction_date,
                                        ), TransactionType::firstWhere('name', 'CRJ'));
                                        $savings_account = SavingsAccount::find($transaction['data']['savings_account_id']);
                                        $transactions[] = [
                                            'account_number' => $savings_account->number,
                                            'account_name' => $savings_account->name,
                                            'reference_number' => $transaction['data']['reference_number'],
                                            'amount' => $transaction['data']['amount'],
                                            'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                            'remarks' => 'SAVINGS WITHDRAWAL'
                                        ];
                                    }
                                }
                                if ($transaction['type'] == 'imprest') {
                                    $isDeposit = $transaction['data']['action'] == 1;
                                    if ($isDeposit) {
                                        app(DepositToImprestAccount::class)->handle($member, new ImprestData(
                                            payment_type_id: $transaction['data']['payment_type_id'],
                                            reference_number: $transaction['data']['reference_number'],
                                            amount: $transaction['data']['amount'],
                                            transaction_date: $this->transaction_date,
                                        ), TransactionType::firstWhere('name', 'CRJ'));
                                        $imprest_account = $member->imprest_account;
                                        $transactions[] = [
                                            'account_number' => $imprest_account->number,
                                            'account_name' => $imprest_account->name,
                                            'reference_number' => $transaction['data']['reference_number'],
                                            'amount' => $transaction['data']['amount'],
                                            'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                            'remarks' => 'IMPREST DEPOSIT'
                                        ];
                                    } else {
                                        app(WithdrawFromImprestAccount::class)->handle($member, new ImprestData(
                                            payment_type_id: $transaction['data']['payment_type_id'],
                                            reference_number: ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
                                            amount: $transaction['data']['amount'],
                                            transaction_date: $this->transaction_date,
                                        ), TransactionType::firstWhere('name', 'CRJ'));
                                        $imprest_account = $member->imprest_account;
                                        $transactions[] = [
                                            'account_number' => $imprest_account->number,
                                            'account_name' => $imprest_account->name,
                                            'reference_number' => $transaction['data']['reference_number'],
                                            'amount' => $transaction['data']['amount'],
                                            'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                            'remarks' => 'IMPREST WITHDRAWAL'
                                        ];
                                    }
                                }
                                if ($transaction['type'] == 'love_gift') {
                                    $isDeposit = $transaction['data']['action'] == 1;
                                    if ($isDeposit) {
                                        app(DepositToLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                                            payment_type_id: $transaction['data']['payment_type_id'],
                                            reference_number: $transaction['data']['reference_number'],
                                            amount: $transaction['data']['amount'],
                                            transaction_date: $this->transaction_date,
                                        ), TransactionType::firstWhere('name', 'CRJ'));
                                        $love_gift_account = $member->love_gift_account;
                                        $transactions[] = [
                                            'account_number' => $love_gift_account->number,
                                            'account_name' => $love_gift_account->name,
                                            'reference_number' => $transaction['data']['reference_number'],
                                            'amount' => $transaction['data']['amount'],
                                            'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                            'remarks' => 'LOVE GIFT DEPOSIT'
                                        ];
                                    } else {
                                        app(WithdrawFromLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                                            payment_type_id: $transaction['data']['payment_type_id'],
                                            reference_number: LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE,
                                            amount: $transaction['data']['amount'],
                                            transaction_date: $this->transaction_date,
                                        ), TransactionType::firstWhere('name', 'CRJ'));
                                        $love_gift_account = $member->love_gift_account;
                                        $transactions[] = [
                                            'account_number' => $love_gift_account->number,
                                            'account_name' => $love_gift_account->name,
                                            'reference_number' => $transaction['data']['reference_number'],
                                            'amount' => $transaction['data']['amount'],
                                            'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                            'remarks' => 'LOVE GIFT WITHDRAWAL'
                                        ];
                                    }
                                }
                                if ($transaction['type'] == 'time_deposit') {
                                    $td = app(CreateTimeDeposit::class)->handle(timeDepositData: new TimeDepositData(
                                        member_id: $member->id,
                                        maturity_date: TimeDepositsProvider::getMaturityDate(today()),
                                        reference_number: $transaction['data']['reference_number'],
                                        payment_type_id: $transaction['data']['payment_type_id'],
                                        amount: $transaction['data']['amount'],
                                        maturity_amount: TimeDepositsProvider::getMaturityAmount(floatval($transaction['data']['amount'])),
                                        transaction_date: $this->transaction_date,
                                    ), transactionType: TransactionType::firstWhere('name', 'CRJ'));
                                    $time_deposit_account = $td->time_deposit_account;
                                    $transactions[] = [
                                        'account_number' => $time_deposit_account->number,
                                        'account_name' => $time_deposit_account->name,
                                        'reference_number' => $transaction['data']['reference_number'],
                                        'amount' => $transaction['data']['amount'],
                                        'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                        'remarks' => 'TIME DEPOSIT'
                                    ];
                                }
                                if ($transaction['type'] == 'cash_collection') {
                                    $cashCollectible = CashCollectible::find($transaction['data']['cash_collectible_id']);
                                    app(PayCashCollectible::class)->handle($cashCollectible, new CashCollectiblePaymentData(
                                        member_id: $member->id,
                                        payee: $transaction['data']['payee'],
                                        payment_type_id: $transaction['data']['payment_type_id'],
                                        reference_number: $transaction['data']['reference_number'],
                                        amount: $transaction['data']['amount'],
                                        transaction_date: $this->transaction_date,
                                    ), TransactionType::firstWhere('name', 'CRJ'));
                                    $transactions[] = [
                                        'account_number' => '',
                                        'account_name' => '',
                                        'reference_number' => $transaction['data']['reference_number'],
                                        'amount' => $transaction['data']['amount'],
                                        'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                        'remarks' => 'CASH COLLECTIBLE PAYMENT: ' . strtoupper($cashCollectible->name)
                                    ];
                                }
                                if ($transaction['type'] == 'loan') {
                                    $loan_account = LoanAccount::find($transaction['data']['loan_account_id']);
                                    $loan = $loan_account?->loan;
                                    app(PayLoan::class)->handle($loan, new LoanPaymentData(
                                        payment_type_id: $transaction['data']['payment_type_id'],
                                        reference_number: $transaction['data']['reference_number'],
                                        amount: $transaction['data']['amount'],
                                        remarks: $transaction['data']['remarks'],
                                        transaction_date: $this->transaction_date,
                                    ), TransactionType::firstWhere('name', 'CRJ'));
                                    $transactions[] = [
                                        'account_number' => $loan_account->number,
                                        'account_name' => $loan_account->name,
                                        'reference_number' => $transaction['data']['reference_number'],
                                        'amount' => $transaction['data']['amount'],
                                        'payment_type' => $payment_types->firstWhere('id', $transaction['data']['payment_type_id'])?->name ?? 'CASH',
                                        'remarks' => 'LOAN PAYMENT'
                                    ];
                                }
                            }
                            DB::commit();
                            Notification::make()->title('Transactions successful!')->success()->send();
                            $this->form->fill();
                            $this->replaceMountedAction('receipt', ['transactions' => $transactions]);
                        }),
                ]),
            ]);
    }

    public function receipt(): ActionsAction
    {
        return ActionsAction::make('receipt')
            ->requiresConfirmation()
            ->modalContent(fn ($arguments) => view('filament.app.pages.cashier.transaction-receipt', ['transactions' => $arguments['transactions']]))
            ->modalWidth(MaxWidth::FourExtraLarge)
            ->modalCancelAction(false)
            ->modalHeading(false)
            ->closeModalByClickingAway(false)
            ->action(function ($arguments) {
            });
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
