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

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public $data = [];

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('month')
                ->options(oxy_get_month_range())
                ->selectablePlaceholder(false)
                ->default(today()->month)
                ->live(),
            Select::make('year')
                ->options(oxy_get_year_range())
                ->selectablePlaceholder(false)
                ->default(today()->year)
                ->live(),
        ])
            ->columns(4)
            ->statePath('data');
    }

    public function mount()
    {
        $this->form->fill();
    }

    #[Computed]
    public function Accounts()
    {
        return FinancialStatementProvider::getAccountsSummary($this->data['month'], $this->data['year']);
    }

    #[Computed]
    public function BalanceForwardedDate()
    {
        return CarbonImmutable::create(month: $this->data['month'], year: $this->data['year'])->subMonthNoOverflow();
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
