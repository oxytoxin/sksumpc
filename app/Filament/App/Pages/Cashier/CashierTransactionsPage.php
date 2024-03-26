<?php

namespace App\Filament\App\Pages\Cashier;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\CashCollections\PayCashCollectible;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\Loans\PayLoan;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Models\CapitalSubscription;
use App\Models\CapitalSubscriptionBilling;
use App\Models\CashCollectible;
use App\Models\LoanAccount;
use App\Models\LoanBilling;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use DB;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;

use function Filament\Support\format_money;

class CashierTransactionsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.cashier.cashier-transactions-page';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    public $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('transactions')
                    ->activeTab(1)
                    ->tabs([
                        Tab::make('Payments')->schema([
                            Livewire::make(PaymentTransactions::class)
                        ]),
                        Tab::make('Billings')
                            ->schema([
                                Livewire::make(BillingTransactions::class),
                            ]),
                    ])

            ]);
    }
}
