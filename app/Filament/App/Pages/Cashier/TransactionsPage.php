<?php

namespace App\Filament\App\Pages\Cashier;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\CashCollections\PayCashCollectible;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\Loans\PayLoan;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\CapitalSubscription;
use App\Models\CashCollectible;
use App\Models\Loan;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use DB;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

use function Filament\Support\format_money;

class TransactionsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static string $view = 'filament.app.pages.transactions-page';

    protected static ?string $navigationLabel = 'Transaction';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    public function getHeading(): string|Htmlable
    {
        return 'Transaction';
    }

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
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
                Select::make('capital_subscription_id')
                    ->label('Capital Subscription')
                    ->options(fn ($get) => CapitalSubscription::whereMemberId($get('member_id'))->where('outstanding_balance', '>', 0)->pluck('code', 'id'))
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('capital_subscription_payments'),
                TextInput::make('amount')->required()
                    ->moneymask(),
                TextInput::make('remarks'),
                DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),
            ])
            ->action(function ($data) {
                $record = CapitalSubscription::find($data['capital_subscription_id']);
                unset($data['member_id'], $data['capital_subscription_id']);
                app(PayCapitalSubscription::class)->handle($record, CapitalSubscriptionPaymentData::from($data), TransactionType::firstWhere('name', 'CRJ'));
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
                    ->options(fn ($get) => SavingsAccount::whereMemberId($get('member_id'))->pluck('name', 'id'))
                    ->label('Account')
                    ->required(),
                Placeholder::make('member_type')
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
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
            ])
            ->action(function ($data) {
                $isDeposit = $data['action'] == 1;
                $member = Member::find($data['member_id']);
                if ($isDeposit) {
                    app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: $data['reference_number'],
                        amount: $data['amount'],
                        savings_account_id: $data['savings_account_id']
                    ), TransactionType::firstWhere('name', 'CRJ'));
                } else {
                    app(WithdrawFromSavingsAccount::class)->handle($member, new SavingsData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: SavingsProvider::WITHDRAWAL_TRANSFER_CODE,
                        amount: $data['amount'],
                        savings_account_id: $data['savings_account_id']
                    ), TransactionType::firstWhere('name', 'CRJ'));
                }
                Notification::make()->title('Savings transaction completed!')->success()->send();
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
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
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
            ])
            ->action(function ($data) {
                $isDeposit = $data['action'] == 1;
                $member = Member::find($data['member_id']);
                if ($isDeposit) {
                    app(DepositToImprestAccount::class)->handle($member, new ImprestData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: $data['reference_number'],
                        amount: $data['amount']
                    ), TransactionType::firstWhere('name', 'CRJ'));
                } else {
                    app(WithdrawFromImprestAccount::class)->handle($member, new ImprestData(
                        payment_type_id: $data['payment_type_id'],
                        reference_number: ImprestsProvider::WITHDRAWAL_TRANSFER_CODE,
                        amount: $data['amount']
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
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
                DatePicker::make('transaction_date')->required()->default(today())->native(false)->live()->afterStateUpdated(fn (Set $set, $state) => $set('maturity_date', TimeDepositsProvider::getMaturityDate($state))),
                DatePicker::make('maturity_date')->required()->readOnly()->default(TimeDepositsProvider::getMaturityDate(today()))->native(false),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('time_deposits'),
                TextInput::make('amount')
                    ->required()
                    ->moneymask()
                    ->afterStateUpdated(fn (Set $set, $state) => $set('maturity_amount', TimeDepositsProvider::getMaturityAmount(floatval($state))))
                    ->minValue(TimeDepositsProvider::MINIMUM_DEPOSIT)->default(TimeDepositsProvider::MINIMUM_DEPOSIT),
                Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                Placeholder::make('maturity_amount')->content(fn (Get $get) => format_money(TimeDepositsProvider::getMaturityAmount(floatval($get('amount'))), 'PHP')),
                TextInput::make('tdc_number')->label('TDC Number')->required()->unique('time_deposits', 'tdc_number')->validationAttribute('TDC Number'),
            ])
            ->action(function ($data) {
                DB::beginTransaction();
                TimeDeposit::create([
                    ...$data,
                ]);
                DB::commit();
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
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
                Select::make('loan_id')
                    ->label('Loan')
                    ->options(fn ($get) => Loan::whereMemberId($get('member_id'))->where('posted', true)->where('outstanding_balance', '>', 0)->pluck('reference_number', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($set, $state) => $set('amount', Loan::find($state)->monthly_payment))
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
                $record = Loan::find($data['loan_id']);
                app(PayLoan::class)->handle($record, new LoanPaymentData(
                    payment_type_id: $data['payment_type_id'],
                    reference_number: $data['reference_number'],
                    amount: $data['amount'],
                    remarks: $data['remarks'],
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
                    ->afterStateUpdated(fn ($state, $set) => $set('payee', Member::find($state)?->full_name))
                    ->preload(),
                Placeholder::make('member_type')
                    ->content(fn ($get) => Member::find($get('member_id'))?->member_type->name),
                TextInput::make('payee')
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('cash_collectible_payments'),
                TextInput::make('amount')->required()->moneymask(),
                DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),

            ])
            ->action(function ($data) {
                $cashCollectible = CashCollectible::find($data['cash_collectible_id']);
                app(PayCashCollectible::class)->handle($cashCollectible, new CashCollectiblePaymentData(
                    member_id: $data['member_id'],
                    payee: $data['payee'],
                    payment_type_id: $data['payment_type_id'],
                    reference_number: $data['reference_number'],
                    amount: $data['amount']
                ), TransactionType::firstWhere('name', 'CRJ'));
                Notification::make()->title('Payment made for '.$cashCollectible->name.'!')->success()->send();
            });
    }
}
