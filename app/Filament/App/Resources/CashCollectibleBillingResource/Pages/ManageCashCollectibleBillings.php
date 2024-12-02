<?php

namespace App\Filament\App\Resources\CashCollectibleBillingResource\Pages;

use App\Actions\CashCollectionBilling\CreateIndividualBilling;
use App\Filament\App\Resources\CashCollectibleBillingResource;
use App\Models\CashCollectible;
use App\Models\CashCollectibleAccount;
use App\Models\CashCollectibleBilling;
use App\Models\CashCollectibleBillingPayment;
use App\Models\CashCollectibleSubscription;
use App\Models\Member;
use App\Models\PaymentType;
use Auth;
use DB;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\Computed;

class ManageCashCollectibleBillings extends ManageRecords
{
    protected static string $resource = CashCollectibleBillingResource::class;
    protected ?string $heading = 'Stakeholders';
    protected static ?string $title = 'Stakeholders';

    #[Computed]
    public function UserIsCashier()
    {
        return Auth::user()->can('manage payments');
    }

    #[Computed]
    public function UserIsCbuOfficer()
    {
        return Auth::user()->can('manage cbu');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('subscriptions')
                ->label('Manage Subscriptions')
                ->url(route('filament.app.resources.cash-collectible-subscriptions.index')),
            Actions\CreateAction::make('group')
                ->label('New Group Billing')
                ->action(function ($data) {
                    DB::beginTransaction();
                    $cash_collectible_billing = CashCollectibleBilling::create($data);
                    CashCollectibleSubscription::where('account_id', $data['account_id'])->each(function ($subscription) use ($cash_collectible_billing) {
                        CashCollectibleBillingPayment::create([
                            'cash_collectible_billing_id' => $cash_collectible_billing->id,
                            'account_id' => $subscription->account_id,
                            'member_id' => $subscription->member_id,
                            'payee' => $subscription->payee,
                            'amount_due' => $subscription->billable_amount,
                            'amount_paid' => $subscription->billable_amount,
                        ]);
                    });
                    DB::commit();
                    Notification::make()->title('New group billing created.')->success()->send();
                })
                ->createAnother(false),
            Actions\CreateAction::make('individual')
                ->label('New Individual Billing')
                ->form([
                    Select::make('account_id')
                        ->options(CashCollectibleAccount::pluck('name', 'id'))
                        ->label('Cash Collectible Account')
                        ->required(),
                    Select::make('payment_type_id')
                        ->paymenttype()
                        ->default(null)
                        ->selectablePlaceholder(true),
                    DatePicker::make('date')
                        ->disabled()
                        ->dehydrated()
                        ->default(config('app.transaction_date'))
                        ->required()
                        ->native(false),
                    Select::make('member_id')
                        ->label('Member')
                        ->options(Member::pluck('full_name', 'id'))
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn($set, $state) => $set('payee', Member::find($state)?->full_name))
                        ->preload(),
                    TextInput::make('payee')
                        ->required(),
                    TextInput::make('amount')
                        ->moneymask(),
                ])
                ->action(function ($data) {
                    app(CreateIndividualBilling::class)->handle(
                        payment_type_id: $data['payment_type_id'],
                        account_id: $data['account_id'],
                        date: $data['date'],
                        member_id: $data['member_id'],
                        payee: $data['payee'],
                        amount: $data['amount'],
                    );
                    Notification::make()->title('New individual billing created.')->success()->send();
                })
                ->createAnother(false),
        ];
    }
}
