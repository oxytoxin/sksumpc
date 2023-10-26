<?php

namespace App\Filament\App\Pages\Cashier;

use App\Models\CapitalSubscription;
use App\Models\CashCollectible;
use App\Models\Loan;
use App\Models\Member;
use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use App\Oxytoxin\SavingsData;
use App\Oxytoxin\SavingsProvider;
use DB;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\RawJs;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class TransactionsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static string $view = 'filament.app.pages.transactions-page';

    protected static ?string $navigationLabel = 'Transaction';

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
                                ->form([
                                    Select::make('member_id')
                                        ->label('Member')
                                        ->options(Member::pluck('full_name', 'id'))
                                        ->searchable()
                                        ->live()
                                        ->required()
                                        ->preload(),
                                    Select::make('capital_subscription_id')
                                        ->label('Capital Subscription')
                                        ->options(fn ($get) => CapitalSubscription::whereMemberId($get('member_id'))->pluck('code', 'id'))
                                        ->required(),
                                    Select::make('type')
                                        ->options([
                                            'OR' => 'OR',
                                            'JV' => 'JV',
                                            'CV' => 'CV',
                                        ])
                                        ->default('OR')
                                        ->selectablePlaceholder(false)
                                        ->live()
                                        ->required(),
                                    TextInput::make('reference_number')->required()
                                        ->unique('capital_subscription_payments'),
                                    TextInput::make('amount')->required()
                                        ->mask(fn ($state) => RawJs::make('$money'))
                                        ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                                        ->minValue(1)->prefix('P'),
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
                                    Select::make('action')
                                        ->options([
                                            '-1' => 'Withdraw',
                                            '1' => 'Deposit'
                                        ])
                                        ->default('1')
                                        ->required(),
                                    DatePicker::make('transaction_date')->required()->default(today()),
                                    TextInput::make('reference_number')->required()
                                        ->unique('savings'),
                                    TextInput::make('amount')->prefix('PHP')
                                        ->required()
                                        ->numeric()
                                        ->minValue(1),
                                ])
                                ->action(function ($data) {
                                    $data['amount'] = $data['amount'] * $data['action'];
                                    DB::beginTransaction();
                                    $member =  Member::find($data['member_id']);
                                    unset($data['member_id'], $data['action']);
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
                                    Select::make('action')
                                        ->options([
                                            '-1' => 'Withdraw',
                                            '1' => 'Deposit'
                                        ])
                                        ->default('1')
                                        ->required(),
                                    DatePicker::make('transaction_date')->required()->default(today()),
                                    TextInput::make('reference_number')->required()
                                        ->unique('imprests'),
                                    TextInput::make('amount')->prefix('PHP')
                                        ->required()
                                        ->numeric()
                                        ->minValue(1),
                                ])
                                ->action(function ($data) {
                                    $data['amount'] = $data['amount'] * $data['action'];
                                    DB::beginTransaction();
                                    $member =  Member::find($data['member_id']);
                                    unset($data['member_id'], $data['action']);
                                    ImprestsProvider::createImprest($member, (new ImprestData(...$data)));
                                    DB::commit();
                                    Notification::make()->title('Imprests transaction completed!')->success()->send();
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
                                    Select::make('loan_id')
                                        ->label('Loan')
                                        ->options(fn ($get) => Loan::whereMemberId($get('member_id'))->where('outstanding_balance', '>', 0)->pluck('reference_number', 'id'))
                                        ->searchable()
                                        ->live()
                                        ->afterStateUpdated(fn ($set, $state) => $set('amount', Loan::find($state)->monthly_payment))
                                        ->required()
                                        ->preload(),
                                    Select::make('type')
                                        ->options([
                                            'OR' => 'OR',
                                            'JV' => 'JV',
                                            'CV' => 'CV',
                                        ])
                                        ->default('OR')
                                        ->selectablePlaceholder(false)
                                        ->live()
                                        ->required(),
                                    TextInput::make('reference_number')->required()
                                        ->unique('loan_payments'),
                                    TextInput::make('amount')->required()->numeric()->minValue(1)->prefix('P'),
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
                                        ->afterStateUpdated(fn ($state, $set) => $set('payee', Member::find($state)->full_name))
                                        ->required()
                                        ->preload(),
                                    TextInput::make('payee')
                                        ->required(),
                                    TextInput::make('reference_number')->required()
                                        ->unique('cash_collectible_payments'),
                                    TextInput::make('amount')->required()->numeric()->minValue(1)->prefix('P'),
                                    DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),

                                ])
                                ->action(function ($data) {
                                    $record = CashCollectible::find($data['cash_collectible_id']);
                                    unset($data['cash_collectible_id']);
                                    $record->payments()->create($data);
                                    Notification::make()->title('Payment made for ' . $record->name . ' !')->success()->send();
                                }),
                        ])
                    ]),
                Section::make('Reports')
                    ->schema([
                        Actions::make([
                            Action::make('daily_summary_savings')
                                ->label('Daily Summary for Savings')
                                ->url(route('filament.app.pages.daily-summary-savings'))
                        ])
                    ]),
            ]);
    }
}
