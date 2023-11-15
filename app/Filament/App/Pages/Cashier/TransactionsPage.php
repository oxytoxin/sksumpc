<?php

namespace App\Filament\App\Pages\Cashier;

use DB;
use App\Models\Loan;
use App\Models\Member;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\TimeDeposit;
use Filament\Support\RawJs;
use App\Oxytoxin\ImprestData;
use App\Oxytoxin\SavingsData;
use App\Models\CashCollectible;
use App\Oxytoxin\SavingsProvider;
use App\Oxytoxin\ImprestsProvider;
use App\Models\CapitalSubscription;
use Filament\Forms\Components\Select;
use App\Oxytoxin\TimeDepositsProvider;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;
use function Filament\Support\format_money;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class TransactionsPage extends Page implements HasForms
{
    use InteractsWithForms;

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Select Action')
                    ->schema([
                        Actions::make([
                            Action::make('pay_cbu')
                                ->label('Pay CBU')
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
                                    $record->payments()->create($data);
                                    Notification::make()->title('Payment made for capital subscription!')->success()->send();
                                }),
                            Action::make('Savings')
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
                                            '1' => 'Deposit'
                                        ])
                                        ->live()
                                        ->default('1')
                                        ->required(),
                                    DatePicker::make('transaction_date')->required()->default(today()),
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
                                    $data['amount'] = $data['amount'] * $data['action'];
                                    DB::beginTransaction();
                                    $member =  Member::find($data['member_id']);
                                    unset($data['member_id'], $data['action']);
                                    $data['reference_number'] ??= '';
                                    SavingsProvider::createSavings($member, (new SavingsData(...$data)));
                                    DB::commit();
                                    Notification::make()->title('Savings transaction completed!')->success()->send();
                                }),
                            Action::make('Imprests')
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
                                            '1' => 'Deposit'
                                        ])
                                        ->live()
                                        ->default('1')
                                        ->required(),
                                    DatePicker::make('transaction_date')->required()->default(today()),
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
                                    $data['amount'] = $data['amount'] * $data['action'];
                                    DB::beginTransaction();
                                    $member =  Member::find($data['member_id']);
                                    unset($data['member_id'], $data['action']);
                                    $data['reference_number'] ??= '';
                                    ImprestsProvider::createImprest($member, (new ImprestData(...$data)));
                                    DB::commit();
                                    Notification::make()->title('Imprests transaction completed!')->success()->send();
                                }),
                            Action::make('New Time Deposit')
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
                                }),
                            Action::make('pay_loan')
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
                                    unset($data['member_id'], $data['loan_id']);
                                    $record->payments()->create($data);
                                    Notification::make()->title('Payment made for loan!')->success()->send();
                                }),
                            Action::make('cash_collectibles')
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
                                    $record = CashCollectible::find($data['cash_collectible_id']);
                                    unset($data['cash_collectible_id']);
                                    $record->payments()->create($data);
                                    Notification::make()->title('Payment made for ' . $record->name . '!')->success()->send();
                                }),
                        ])
                    ]),

            ]);
    }
}
