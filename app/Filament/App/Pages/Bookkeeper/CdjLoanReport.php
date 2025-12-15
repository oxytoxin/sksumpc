<?php

namespace App\Filament\App\Pages\Bookkeeper;

use Filament\Schemas\Schema;
use App\Models\Loan;
use App\Models\LoanType;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class CdjLoanReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \UnitEnum | null $navigationGroup = 'Bookkeeping';

    protected string $view = 'filament.app.pages.bookkeeper.cdj-loan-report';

    protected static ?string $title = 'CDJ SL - Loans Receivables';

    protected static ?string $navigationLabel = 'CDJ SL - Loans Receivables';

    public $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
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
    }

    #[Computed]
    public function loanTypes()
    {
        return LoanType::get();
    }
}
