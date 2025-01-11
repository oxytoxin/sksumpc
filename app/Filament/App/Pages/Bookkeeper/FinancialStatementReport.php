<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Models\AccountType;
use App\Models\LoanType;
use App\Models\TransactionType;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use Auth;
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
    use InteractsWithActions, InteractsWithForms, RequiresBookkeeperTransactionDate;

    protected static string $view = 'filament.app.pages.bookkeeper.financial-statement-report';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage bookkeeping');
    }

    public $data_loaded = false;

    public $data = [];

    public $load_data = true;

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('mode')
                ->options([
                    'single' => 'Single Month',
                    'comparative' => 'Comparative',
                    // 'period' => 'Period Covered',
                    'yearly' => 'Yearly',
                ])
                ->default('single')
                ->live(),
            Select::make('month')
                ->options(oxy_get_month_range())
                ->default(config('app.transaction_date')?->month)
                ->selectablePlaceholder(false)
                ->visible(fn($get) => in_array($get('mode'), ['single']))
                ->live(),
            Select::make('year')
                ->options(oxy_get_year_range())
                ->default(config('app.transaction_date')?->year)
                ->selectablePlaceholder(false)
                ->visible(fn($get) => in_array($get('mode'), ['single', 'yearly']))
                ->live(),
            DatePicker::make('from')
                ->live()
                ->default(config('app.transaction_date')?->subMonthNoOverflow())
                ->native(false)
                ->displayFormat('m/d/Y')
                ->visible(fn($get) => in_array($get('mode'), ['comparative'])),
            DatePicker::make('to')
                ->live()
                ->default(config('app.transaction_date'))
                ->native(false)
                ->displayFormat('m/d/Y')
                ->visible(fn($get) => in_array($get('mode'), ['comparative'])),
        ])
            ->columns(4)
            ->statePath('data');
    }

    public function mount()
    {
        $this->form->fill();
    }

    #[Computed]
    public function SelectedMonth()
    {
        return CarbonImmutable::create($this->data['year'], $this->data['month']);
    }

    #[Computed]
    public function FromDate()
    {
        return CarbonImmutable::create($this->data['from']);
    }

    #[Computed]
    public function ToDate()
    {
        return CarbonImmutable::create($this->data['to']);
    }

    #[Computed]
    public function TransactionTypes()
    {
        return TransactionType::get();
    }

    #[Computed]
    public function MonthPairs()
    {
        return match ($this->data['mode']) {
            'comparative' => $this->getComparativeMonthPairs(),
            'yearly' => $this->getYearlyMonthPairs(),
        };
    }

    private function getComparativeMonthPairs()
    {
        $pairs = [];
        $current = $this->from_date;
        $end = $this->to_date;
        $pairs[] = [
            'current' => ['index' => 1, 'date' => $current],
            'next' => ['index' => 2, 'date' => $end],
        ];

        return $pairs;
    }

    private function getYearlyMonthPairs()
    {
        $pairs = [];
        $selected_year = CarbonImmutable::create(year: $this->data['year']);
        $current = $selected_year->subYearNoOverflow()->endOfYear();
        $end = $selected_year->endOfYear();
        $index = 0;
        while ($current->format('F Y') != $end->format('F Y')) {
            $next = $current->addMonthNoOverflow()->endOfMonth();
            $pairs[] = [
                'current' => ['index' => $index, 'date' => $current],
                'next' => ['index' => ++$index, 'date' => $next],
            ];
            $current = $next;
        }

        return $pairs;
    }

    #[Computed]
    public function AccountTypes()
    {
        return AccountType::get();
    }

    #[Computed]
    public function FormattedBalanceForwardedDate()
    {
        return match ($this->data['mode']) {
            'yearly' => CarbonImmutable::create(year: $this->data['year'])->subYearNoOverflow()->endOfYear()->format('F Y'),
            'comparative' => $this->from_date->subMonthNoOverflow()->endOfMonth()->format('F d, Y'),
        };
    }

    #[Computed]
    public function ComparativeDates()
    {
        return [$this->from_date, $this->to_date];
    }

    #[Computed]
    public function TrialBalance()
    {
        $data = match ($this->data['mode']) {
            'single' => TrialBalanceProvider::getSingleTrialBalance($this->selected_month),
            'comparative' => TrialBalanceProvider::getComparativeTrialBalance($this->from_date, $this->to_date),
            'yearly' => TrialBalanceProvider::getYearlyTrialBalance($this->data['year']),
        };

        return $data;
    }

    public function loadData()
    {
        $this->data_loaded = true;
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
