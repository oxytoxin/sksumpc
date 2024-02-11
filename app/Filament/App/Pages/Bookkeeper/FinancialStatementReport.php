<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\Loan;
use App\Models\Account;
use App\Models\LoanType;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\TransactionType;
use App\Models\LoanAmortization;
use App\Models\TrialBalanceEntry;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\BalanceForwardedEntry;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

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

    public function updated($name, $value)
    {
        $this->dispatch('dateChanged', $this->data);
    }

    public function mount()
    {
        $this->form->fill();
    }

    #[Computed]
    public function Accounts()
    {
        return Account::withQueryConstraint(function ($query) {
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as total_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'debit')
                ->withSum(['recursiveCrjTransactions as total_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'credit')
                ->withSum(['recursiveCdjTransactions as total_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'debit')
                ->withSum(['recursiveCdjTransactions as total_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'credit')
                ->withSum(['recursiveJevTransactions as total_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'debit')
                ->withSum(['recursiveJevTransactions as total_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'credit')
                ->whereNull('accounts.member_id');
        }, function () {
            $joinSub = BalanceForwardedEntry::whereHas('balance_forwarded_summary', fn ($q) => $q->whereMonth('generated_date', $this->data['month'])->whereYear('generated_date', $this->data['year']));
            return Account::tree()
                ->leftJoinSub($joinSub, 'balance_forwarded_entries', function ($join) {
                    $join->on('laravel_cte.id', '=', 'balance_forwarded_entries.account_id');
                })
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw(
                    'balance_forwarded_entries.balance_forwarded_summary_id as balance_forwarded_summary_id,
                     balance_forwarded_entries.debit as balance_forwarded_debit, 
                     balance_forwarded_entries.credit as balance_forwarded_credit, 
                     laravel_cte.*, 
                     account_types.debit_operator, 
                     account_types.credit_operator, 
                    (
                        coalesce(total_crj_debit, 0) + 
                        coalesce(total_cdj_debit, 0) + 
                        coalesce(total_jev_debit, 0)
                    ) as total_debit, 
                    (
                        coalesce(total_crj_credit, 0) +
                        coalesce(total_cdj_credit, 0) + 
                        coalesce(total_jev_credit, 0)
                    ) as total_credit, 
                        (
                            (
                                coalesce( balance_forwarded_entries.debit, 0) + 
                                coalesce(total_crj_debit, 0) + 
                                coalesce(total_cdj_debit, 0) + 
                                coalesce(total_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce(balance_forwarded_entries.credit, 0) +
                                coalesce(total_crj_credit, 0) +
                                coalesce(total_cdj_credit, 0) + 
                                coalesce(total_jev_credit, 0)
                            ) * credit_operator
                        ) as ending_balance'
                ))->get();
        })->toTree();
    }

    #[Computed]
    public function TransactionTypes()
    {
        return TransactionType::get();
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
