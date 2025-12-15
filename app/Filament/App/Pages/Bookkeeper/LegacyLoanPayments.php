<?php

namespace App\Filament\App\Pages\Bookkeeper;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use App\Actions\Loans\PayLegacyLoan;
use App\Models\LoanAccount;
use App\Models\Member;
use App\Models\TransactionType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LegacyLoanPayments extends Page
{
    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Bookkeeping';

    protected string $view = 'filament.app.pages.bookkeeper.legacy-loan-payments';

    public $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->dehydrated(false)
                    ->label('Member')
                    ->searchable()
                    ->preload()
                    ->options(Member::pluck('full_name', 'id'))
                    ->reactive(),
                Select::make('loan_account_id')
                    ->options(fn ($get) => LoanAccount::when($get('member_id'), fn ($q, $v) => $q->where('member_id', $v))->pluck('number', 'id'))
                    ->searchable()
                    ->reactive()
                    ->label('Loan Account')
                    ->preload(),
                Placeholder::make('loan_type')
                    ->content(fn ($get) => LoanAccount::find($get('loan_account_id'))?->loan?->loan_type?->name),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
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
                            $transactionType = TransactionType::CRJ();
                            app(PayLegacyLoan::class)
                                ->handle(
                                    loanAccount: LoanAccount::find($data['loan_account_id']),
                                    principal: $data['principal'] ?? 0,
                                    interest: $data['interest'] ?? 0,
                                    payment_type_id: $data['payment_type_id'],
                                    reference_number: $data['reference_number'],
                                    transaction_date: $data['transaction_date'],
                                    transactionType: $transactionType
                                );
                            Notification::make()->title('Legacy loan payment posted!')->success()->send();
                            $this->reset();
                        }),
                ]),
            ])
            ->statePath('data');
    }
}
