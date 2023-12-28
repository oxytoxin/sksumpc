<?php

namespace App\Filament\App\Pages\Bookkeeper;

use Livewire;
use App\Models\Loan;
use App\Models\LoanType;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\LoanAmortization;
use Filament\Infolists\Infolist;
use App\Models\TrialBalanceEntry;
use Livewire\Attributes\Computed;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Tabs;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class FinancialStatementReport extends Page implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.app.pages.bookkeeper.financial-statement-report';

    protected static ?string $navigationGroup = 'Bookkeeping';

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
    public function trialBalanceEntries()
    {
        return TrialBalanceEntry::get()->toTree();
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
                $trial_balance_entries = TrialBalanceEntry::withDepth()->defaultOrder()->with('auditable')->get();
                $worksheet->insertNewRowBefore($row, $trial_balance_entries->count());
                $trial_balance_entries_tree = $trial_balance_entries->toFlatTree();
                foreach ($trial_balance_entries_tree as $entry) {
                    $worksheet->setCellValue("$column$row", str_repeat(" ", $entry->depth * 4) . strtoupper($entry->name));
                    $loan_type = $entry->auditable;
                    if ($loan_type && $loan_type instanceof LoanType) {
                        $loan_receivable = LoanAmortization::receivable(loan_type: $loan_type, month: $this->data['month'] ?? null, year: $this->data['year'] ?? null);
                        $loan_disbursed = LoanAmortization::disbursed(loan_type: $loan_type, month: $this->data['month'] ?? null, year: $this->data['year'] ?? null);
                    }
                    if ($entry->parent?->name === 'loans receivable' && $loan_type instanceof LoanType) {
                        $crj_loans_receivable = $loan_receivable->sum('principal_balance');
                        $cdj_loans_receivable = $loan_disbursed->sum('principal_payment');
                        $loan_debit_amount = Loan::posted()->whereLoanTypeId($loan_type->id)->whereMonth('transaction_date', $this->data['month'] ?? null)->whereYear('transaction_date', $this->data['year'] ?? null)->sum('gross_amount');
                        $worksheet->setCellValue("E$row", $crj_loans_receivable);
                        $worksheet->setCellValue("S$row", $cdj_loans_receivable);
                        $worksheet->setCellValue("R$row", $loan_debit_amount);
                    }

                    if ($entry->parent?->name === 'interest income from loans' && $loan_type instanceof LoanType) {
                        $crj_loans_interest = $loan_receivable->sum('interest_balance');
                        $cdj_loans_interest = $loan_disbursed->sum('interest_payment');
                        $worksheet->setCellValue("E$row", $crj_loans_interest);
                        $worksheet->setCellValue("S$row", $cdj_loans_interest);
                    }
                    if ($entry->depth != 0) {
                        $worksheet->getCell("$column$row")->getStyle()->getFont()->setBold(false);
                    }
                    $worksheet->setCellValue("P$row", "=SUM(D$row,F$row,H$row,J$row,L$row,N$row)");
                    $worksheet->setCellValue("Q$row", "=SUM(E$row,G$row,I$row,K$row,M$row,O$row)");
                    $worksheet->setCellValue("Z$row", "=SUM(R$row,T$row,V$row,X$row)");
                    $worksheet->setCellValue("AA$row", "=SUM(S$row,U$row,W$row,Y$row)");
                    $worksheet->setCellValue("AD$row", "=SUM(B$row,P$row,Z$row,AB$row)-SUM(Q$row,AA$row,AC$row)");
                    $worksheet->setCellValue("AE$row", "=SUM(C$row,Q$row,AA$row,AC$row)-SUM(P$row,Z$row,AB$row)");
                    $row++;
                }
                $path = storage_path('app/livewire-tmp/trial_balance-' . today()->year . '.xlsx');
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($path);
                return response()->download($path);
            });
    }
}
