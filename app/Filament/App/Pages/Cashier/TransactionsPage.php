<?php

namespace App\Filament\App\Pages\Cashier;

use App\Models\Member;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use App\Models\LoanAccount;
use Filament\Actions\Action;
use App\Actions\Loans\PayLoan;
use App\Models\SavingsAccount;
use App\Models\CashCollectible;
use App\Models\TransactionType;
use Filament\Support\Colors\Color;
use App\Models\CapitalSubscription;
use App\Models\SystemConfiguration;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use Filament\Forms\Components\Select;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use Filament\Forms\Components\DatePicker;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;
use App\Oxytoxin\Providers\SavingsProvider;
use function Filament\Support\format_money;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\CashCollections\PayCashCollectible;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Actions\CapitalSubscription\PayCapitalSubscription;
use Filament\Forms\Components\Actions\Action as FormAction;

use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;

class TransactionsPage extends Page
{
    use RequiresBookkeeperTransactionDate;

    protected static string $view = 'filament.app.pages.transactions-page';

    protected static ?string $navigationLabel = 'Transaction';

    protected static ?string $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
        return auth()->user()->can('manage payments');
    }

    public function getHeading(): string|Htmlable
    {
        return 'Transaction';
    }

    public $transaction_date;

    public function payCbu(): Action
    {
        return Action::make('payCbu')
            ->label('Pay CBU')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->live()
                    ->required()
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
                Select::make('capital_subscription_id')
                    ->label('Capital Subscription')
                    ->reactive()
                    ->options(fn($get) => CapitalSubscription::whereMemberId($get('member_id'))->where('outstanding_balance', '>', 0)->pluck('code', 'id'))
                    ->afterStateUpdated(function ($state, $set) {
                        $cbu = CapitalSubscription::find($state);
                        if ($cbu) {
                            if ($cbu->payments()->exists()) {
                                $set('amount', $cbu->monthly_payment);
                            } else {
                                $set('amount', $cbu->initial_amount_paid);
                            }
                        }
                    })
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('capital_subscription_payments'),
                TextInput::make('amount')
                    ->required()
                    ->default(function ($get) {
                    })
                    ->moneymask(),
                Placeholder::make('monthly_payment_required')->content(function ($get) {
                    $cbu = CapitalSubscription::find($get('capital_subscription_id'));

                    return $cbu?->monthly_payment ?? 0;
                }),
                TextInput::make('remarks'),
            ])
            ->action(function ($data) {
                $record = CapitalSubscription::find($data['capital_subscription_id']);
                unset($data['member_id'], $data['capital_subscription_id']);
                app(PayCapitalSubscription::class)->handle(
                    $record,
                    new CapitalSubscriptionPaymentData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: $data['reference_number'],
                        amount: $data['amount'],
                        transaction_date: $this->transaction_date,
                    ),
                    TransactionType::firstWhere('name', 'CRJ')
                );
                Notification::make()->title('Payment made for capital subscription!')->success()->send();
            });
    }

    public function paySavings()
    {
        return Action::make('paySavings')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->selectablePlaceholder(false)
                    ->live()
                    ->required()
                    ->preload(),
                Select::make('savings_account_id')
                    ->options(fn($get) => SavingsAccount::whereMemberId($get('member_id'))->pluck('number', 'id'))
                    ->label('Account')
                    ->required()
                    ->suffixAction(
                        fn($get) => FormAction::make('NewAccount')
                            ->label('New Account')
                            ->modalHeading('New Savings Account')
                            ->form([
                                TextInput::make('name')
                                    ->required(),
                            ])
                            ->visible(fn($get) => $get('member_id'))
                            ->action(function ($data, $get) {
                                app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                                    member_id: $get('member_id'),
                                    name: $data['name'],
                                ));
                                Notification::make()->title('Savings account created!')->success()->send();
                            })
                            ->icon('heroicon-m-plus')
                            ->color(Color::Emerald),
                    ),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
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
                    ->visible(fn($get) => $get('action') == '1')
                    ->unique('savings'),
                TextInput::make('amount')
                    ->required()
                    ->moneymask(),
            ])
            ->action(function ($data) {
                $isDeposit = $data['action'] == 1;
                $member = Member::find($data['member_id']);
                if ($isDeposit) {
                    app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: $data['reference_number'],
                        amount: $data['amount'],
                        savings_account_id: $data['savings_account_id'],
                        transaction_date: $this->transaction_date
                    ), TransactionType::firstWhere('name', 'CRJ'));
                } else {
                    app(WithdrawFromSavingsAccount::class)->handle($member, new SavingsData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
                        amount: $data['amount'],
                        savings_account_id: $data['savings_account_id'],
                        transaction_date: $this->transaction_date
                    ), TransactionType::firstWhere('name', 'CRJ'));
                }
                Notification::make()->title('Savings transaction completed!')->success()->send();
            });
    }

    public function payLoveGift()
    {
        return Action::make('payLoveGift')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->selectablePlaceholder(false)
                    ->live()
                    ->required()
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
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
                    ->visible(fn($get) => $get('action') == '1')
                    ->unique('love_gifts'),
                TextInput::make('amount')
                    ->required()
                    ->moneymask(),
            ])
            ->action(function ($data) {
                $isDeposit = $data['action'] == 1;
                $member = Member::find($data['member_id']);
                if ($isDeposit) {
                    app(DepositToLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: $data['reference_number'],
                        amount: $data['amount'],
                        transaction_date: $this->transaction_date
                    ), TransactionType::firstWhere('name', 'CRJ'));
                } else {
                    app(WithdrawFromLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE,
                        amount: $data['amount'],
                        transaction_date: $this->transaction_date
                    ), TransactionType::firstWhere('name', 'CRJ'));
                }
                Notification::make()->title('Love Gift transaction completed!')->success()->send();
            });
    }

    public function payImprests()
    {
        return Action::make('payImprests')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->live()
                    ->required()
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
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
                    ->visible(fn($get) => $get('action') == '1')
                    ->unique('imprests'),
                TextInput::make('amount')
                    ->required()
                    ->moneymask(),
            ])
            ->action(function ($data) {
                $isDeposit = $data['action'] == 1;
                $member = Member::find($data['member_id']);
                if ($isDeposit) {
                    app(DepositToImprestAccount::class)->handle($member, new ImprestData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: $data['reference_number'],
                        amount: $data['amount'],
                        transaction_date: $this->transaction_date
                    ), TransactionType::firstWhere('name', 'CRJ'));
                } else {
                    app(WithdrawFromImprestAccount::class)->handle($member, new ImprestData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
                        amount: $data['amount'],
                        transaction_date: $this->transaction_date
                    ), TransactionType::firstWhere('name', 'CRJ'));
                }
                Notification::make()->title('Imprests transaction completed!')->success()->send();
            });
    }

    public function payTimeDeposit()
    {
        return Action::make('payTimeDeposit')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->live()
                    ->required()
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
                DatePicker::make('transaction_date')->required()->default(today())->native(false)->live()->afterStateUpdated(fn(Set $set, $state) => $set('maturity_date', TimeDepositsProvider::getMaturityDate($state))),
                Placeholder::make('maturity_date')->content(TimeDepositsProvider::getMaturityDate(today())->format('F d, Y')),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('time_deposits'),
                TextInput::make('amount')
                    ->required()
                    ->moneymask()
                    ->afterStateUpdated(fn(Set $set, $state) => $set('maturity_amount', TimeDepositsProvider::getMaturityAmount(floatval($state))))
                    ->minValue(TimeDepositsProvider::MINIMUM_DEPOSIT)->default(TimeDepositsProvider::MINIMUM_DEPOSIT),
                Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                Placeholder::make('maturity_amount')->content(fn(Get $get) => format_money(TimeDepositsProvider::getMaturityAmount(floatval($get('amount'))), 'PHP')),
            ])
            ->action(function ($data) {
                app(CreateTimeDeposit::class)->handle(timeDepositData: new TimeDepositData(
                    member_id: $data['member_id'],
                    maturity_date: TimeDepositsProvider::getMaturityDate(today()),
                    reference_number: $data['reference_number'],
                    payment_type_id: $data['payment_type_id'],
                    amount: $data['amount'],
                    maturity_amount: TimeDepositsProvider::getMaturityAmount(floatval($data['amount'])),
                    transaction_date: $this->transaction_date
                ), transactionType: TransactionType::firstWhere('name', 'CRJ'));
                Notification::make()->title('Time deposit transaction completed!')->success()->send();
            });
    }

    public function payLoan()
    {
        return Action::make('payLoan')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->live()
                    ->required()
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
                Select::make('loan_account_id')
                    ->label('Loan Account')
                    ->options(fn($get) => LoanAccount::whereMemberId($get('member_id'))->whereHas('loan', fn($q) => $q->where('posted', true)->where('outstanding_balance', '>', 0))->pluck('number', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn($set, $state) => $set('amount', LoanAccount::find($state)?->loan?->monthly_payment))
                    ->required()
                    ->preload(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('loan_payments'),
                TextInput::make('amount')->required()->moneymask(),
                TextInput::make('remarks'),
                DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),

            ])
            ->action(function ($data) {
                $loan = LoanAccount::find($data['loan_account_id'])?->loan;
                app(PayLoan::class)->handle($loan, new LoanPaymentData(
                    payment_type_id: $data['payment_type_id'],
                    reference_number: $data['reference_number'],
                    amount: $data['amount'],
                    remarks: $data['remarks'],
                    transaction_date: $this->transaction_date
                ), TransactionType::firstWhere('name', 'CRJ'));
                Notification::make()->title('Payment made for loan!')->success()->send();
            });
    }

    public function payCashCollection()
    {
        return Action::make('payCashCollection')
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->slideOver(true)
            ->form([
                Select::make('cash_collectible_id')
                    ->options(CashCollectible::pluck('name', 'id'))
                    ->label('Cash Collectible')
                    ->required(),
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn($state, $set) => $set('payee', Member::find($state)?->full_name))
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
                TextInput::make('payee')
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('cash_collectible_payments'),
                TextInput::make('amount')->required()->moneymask(),
            ])
            ->action(function ($data) {
                $cashCollectible = CashCollectible::find($data['cash_collectible_id']);
                app(PayCashCollectible::class)->handle($cashCollectible, new CashCollectiblePaymentData(
                    member_id: $data['member_id'],
                    payee: $data['payee'],
                    payment_type_id: $data['payment_type_id'],
                    reference_number: $data['reference_number'],
                    amount: $data['amount'],
                    transaction_date: $this->transaction_date
                ), TransactionType::firstWhere('name', 'CRJ'));
                Notification::make()->title('Payment made for ' . $cashCollectible->name . '!')->success()->send();
            });
    }
}
