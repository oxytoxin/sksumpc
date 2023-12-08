<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\Loan;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\LoanAmortization;
use App\Models\LoanType;
use Livewire\Attributes\Computed;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class CdjLoanReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.bookkeeper.cdj-loan-report';

    protected ?string $heading = 'CDJ SL - Loans Receivables';

    protected static ?string $navigationLabel = 'CDJ SL - Loans Receivables';

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
    public function receivables()
    {
        return Loan::posted()->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])->get();
        // return LoanAmortization::whereBillableDate(str(oxy_get_month_range()[$this->data['month']])->append(' ')->append($this->data['year']))->with('loan.member')->get();
    }
    #[Computed]
    public function loan_types()
    {
        return LoanType::get();
    }
}
