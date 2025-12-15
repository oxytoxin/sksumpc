<?php

namespace App\Filament\App\Pages\Cashier;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\Savings\GenerateAccountNumber;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class NewAccountTransactions extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.app.pages.cashier.new-account-transactions';

    public $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->label('Member')
                    ->afterStateUpdated(function ($set, $state) {
                        $set('name', Member::find($state)?->full_name);
                        $set('number', app(GenerateAccountNumber::class)->handle(member_type_id: Member::find($state)?->member_type_id));
                    })
                    ->reactive(),
                TextInput::make('name')
                    ->label('Account Name')
                    ->required(),
                TextInput::make('number')
                    ->label('Account Number')
                    ->unique('accounts', 'number')
                    ->required(),
                Actions::make([
                    Action::make('create')
                        ->action(function () {
                            $data = $this->form->getState();
                            app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                                member_id: $data['member_id'],
                                name: $data['name'],
                                number: $data['number']
                            ));
                            Notification::make()->title('Savings account created!')->success()->send();
                            $this->reset('data');
                        }),
                ]),
            ])
            ->statePath('data');
    }

    public function mount()
    {
        $this->form->fill();
    }
}
