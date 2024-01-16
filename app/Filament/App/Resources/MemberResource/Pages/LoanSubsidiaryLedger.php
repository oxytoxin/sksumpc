<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Oxytoxin\Providers\LoansProvider;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

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

    public function getHeading(): string|Htmlable
    {
        return 'Loan Subsidiary Ledger';
    }
}
