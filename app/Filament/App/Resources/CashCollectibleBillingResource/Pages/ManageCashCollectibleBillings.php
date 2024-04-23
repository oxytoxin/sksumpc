<?php

namespace App\Filament\App\Resources\CashCollectibleBillingResource\Pages;

use App\Actions\CashCollectionBilling\CreateIndividualBilling;
use App\Filament\App\Resources\CashCollectibleBillingResource;
use App\Models\CashCollectible;
use App\Models\Member;
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

    #[Computed]
    public function UserIsCashier()
    {
        return auth()->user()->can('manage payments');
    }

    #[Computed]
    public function UserIsCbuOfficer()
    {
        return auth()->user()->can('manage cbu');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('group')
                ->label('New Group Billing')
                ->createAnother(false),
            Actions\CreateAction::make('individual')
                ->label('New Individual Billing')
                ->form([
                    Select::make('cash_collectible_id')
                        ->options(CashCollectible::pluck('name', 'id'))
                        ->label('Cash Collectible')
                        ->required(),
                    Select::make('payment_type_id')
                        ->paymenttype()
                        ->default(null)
                        ->selectablePlaceholder(true),
                    DatePicker::make('date')
                        ->date()
                        // ->afterOrEqual(today())
                        // ->validationMessages([
                        //     'after_or_equal' => 'The date must be after or equal to today.',
                        // ])
                        ->required()
                        ->native(false),
                    Select::make('member_id')
                        ->label('Member')
                        ->options(Member::pluck('full_name', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($set, $state) => $set('payee', Member::find($state)?->full_name))
                        ->preload(),
                    TextInput::make('payee')
                        ->required(),
                    TextInput::make('amount')
                        ->moneymask(),
                ])
                ->action(function ($data) {
                    app(CreateIndividualBilling::class)->handle(
                        payment_type_id: $data['payment_type_id'],
                        cash_collectible_id: $data['cash_collectible_id'],
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
