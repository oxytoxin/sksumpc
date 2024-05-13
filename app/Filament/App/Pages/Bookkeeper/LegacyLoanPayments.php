<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Actions\Loans\PayLegacyLoan;
use App\Models\LoanAccount;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LegacyLoanPayments extends Page
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bookkeeping';

    protected static string $view = 'filament.app.pages.bookkeeper.legacy-loan-payments';

    public $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('loan_account_id')
                    ->options(LoanAccount::pluck('number', 'id'))
                    ->searchable()
                    ->reactive()
                    ->label('Loan Account')
                    ->preload(),
                Placeholder::make('member')->content(fn ($get) => LoanAccount::find($get('loan_account_id'))?->member?->full_name),
                TextInput::make('reference_number')
                    ->required(),
                TextInput::make('principal')
                    ->moneymask(),
                TextInput::make('interest')
                    ->moneymask(),
                Placeholder::make('total')->content(fn ($get) => floatval($get('principal')) + floatval($get('interest'))),
                DatePicker::make('transaction_date')
                    ->default(config('app.transaction_date') ?? today())
                    ->native(false)
                    ->required(),
                Actions::make([
                    Action::make('submit')
                        ->requiresConfirmation()
                        ->action(function () {
                            $data = $this->form->getState();
                            app(PayLegacyLoan::class)
                                ->handle(
                                    loanAccount: LoanAccount::find($data['loan_account_id']),
                                    principal: $data['principal'],
                                    interest: $data['interest'],
                                    reference_number: $data['reference_number'],
                                    transaction_date: $data['transaction_date'],
                                );
                            Notification::make()->title('Legacy loan payment posted!')->success()->send();
                            $this->reset();
                        })
                ])
            ])
            ->statePath('data');
    }
}
