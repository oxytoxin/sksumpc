<?php

namespace App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;

use Filament\Actions\CreateAction;
use App\Actions\CapitalSubscriptionBilling\CreateIndividualBilling;
use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\CapitalSubscriptionBillingResource;
use App\Models\Member;
use Auth;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\Computed;

class ManageCapitalSubscriptionBillings extends ManageRecords
{
    protected static string $resource = CapitalSubscriptionBillingResource::class;

    use RequiresBookkeeperTransactionDate;

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
            CreateAction::make('group')
                ->label('New Group Billing')
                ->createAnother(false),
            CreateAction::make('individual')
                ->label('New Individual Billing')
                ->schema([
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
                        ->required()
                        ->preload(),
                ])
                ->action(function ($data) {
                    app(CreateIndividualBilling::class)->handle(
                        payment_type_id: $data['payment_type_id'],
                        date: $data['date'],
                        member_id: $data['member_id']
                    );
                    Notification::make()->title('New individual billing created.')->success()->send();
                })
                ->createAnother(false),
        ];
    }
}
