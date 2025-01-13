<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Oxytoxin\Providers\LoansProvider;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Computed;

class LoanSubsidiaryLedger extends Page
{
    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.loan-subsidiary-ledger';

    public Loan $loan;

    public $schedule = [];

    public function mount()
    {
        $this->schedule = LoansProvider::generateAmortizationSchedule($this->loan);
    }

    #[Computed]
    public function AccruedInterest(): float
    {
        $start = $this->loan->last_payment?->transaction_date ?? $this->loan->transaction_date;
        $end = config('app.transaction_date') ?? today();
        $total_days = LoansProvider::getAccruableDays($start, $end);
        $interest_due = LoansProvider::computeAccruedInterest($this->loan, $this->loan->outstanding_balance, $total_days);
        return $interest_due;
    }

    public function getHeading(): string|Htmlable
    {
        return 'Loan Subsidiary Ledger';
    }
}
