<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\AccountType;
use App\Models\LoanType;
use App\Models\TransactionType;
use App\Oxytoxin\Providers\FinancialStatementProvider;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FinancialStatementReport extends Page implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.app.pages.bookkeeper.financial-statement-report';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public $data = [];

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('transaction_date')
                ->default(today())
                ->native(false)
                ->reactive(),
            Select::make('mode')
                ->options([
                    'daily' => 'Daily',
                    'monthly' => 'Monthly'
                ])
                ->default('monthly')
                ->selectablePlaceholder(false)
                ->reactive(),
        ])
            ->columns(4)
            ->statePath('data');
    }

    public function mount()
    {
        $this->form->fill();
        $this->data['transaction_date'] = '01/01/2024';
    }

    #[Computed]
    public function Accounts()
    {
        if ($this->data['mode'] == 'daily') {
            $date = date_create($this->data['transaction_date'] ?? today());
            return FinancialStatementProvider::getDailyAccountsSummary($date);
        }
        $transaction_date = CarbonImmutable::create($this->data['transaction_date'] ?? today());
        return FinancialStatementProvider::getAccountsSummary(month: $transaction_date->month, year: $transaction_date->year);
    }

    #[Computed]
    public function BalanceForwardedDate()
    {
        return CarbonImmutable::create($this->data['transaction_date'])->subMonthNoOverflow();
    }

    #[Computed]
    public function TransactionDate()
    {
        return CarbonImmutable::create($this->data['transaction_date']);
    }

    #[Computed]
    public function TransactionTypes()
    {
        return TransactionType::get();
    }

    #[Computed]
    public function AccountTypes()
    {
        return AccountType::get();
    }

    public function downloadTrialBalance()
    {
        return Action::make('downloadTrialBalance')
            ->action(function () {
                $loan_types = LoanType::get();
                $column = 'A';
                $row = 4;
                $spreadsheet = IOFactory::load(storage_path('templates/trial_balance.xlsx'));
                $worksheet = $spreadsheet->getActiveSheet();

                $path = storage_path('app/livewire-tmp/trial_balance-' . today()->year . '.xlsx');
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($path);

                return response()->download($path);
            });
    }
}
