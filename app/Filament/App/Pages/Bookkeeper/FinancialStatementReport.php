<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\AccountType;
use App\Models\LoanType;
use App\Models\TransactionType;
use App\Oxytoxin\Providers\FinancialStatementProvider;
use App\Oxytoxin\Providers\TrialBalanceProvider;
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

    public $data_loaded = false;
    public $data = [];

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('year')
                ->options(oxy_get_year_range())
                ->default(config('app.transaction_date', today())->year)
                ->selectablePlaceholder(false)
                ->reactive(),
        ])
            ->columns(4)
            ->statePath('data');
    }

    public function mount()
    {
        $this->form->fill();
        // $this->data['year'] = 2024;
    }

    #[Computed]
    public function TransactionTypes()
    {
        return TransactionType::get();
    }

    #[Computed]
    public function MonthPairs()
    {
        $pairs = [];
        $selected_year = CarbonImmutable::create(year: $this->data['year']);
        $current = $selected_year->subYearNoOverflow()->endOfYear();
        $end = $selected_year->endOfYear();
        $index = 0;
        while ($current->format('F Y') != $end->format('F Y')) {
            $next = $current->addMonthNoOverflow();
            $pairs[] = [
                'current' => ['index' => $index++, 'date' => $current],
                'next' => ['index' => $index++, 'date' => $next],
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
        return CarbonImmutable::create(year: $this->data['year'])->subYearNoOverflow()->endOfYear()->format('F Y');
    }

    #[Computed]
    public function TrialBalance()
    {
        $data = TrialBalanceProvider::getTrialBalance($this->data['year']);
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
