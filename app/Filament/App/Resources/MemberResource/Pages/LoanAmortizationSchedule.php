<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Oxytoxin\LoansProvider;
use Filament\Resources\Pages\Page;

class LoanAmortizationSchedule extends Page
{
    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.loan-amortization-schedule';

    public Loan $loan;
    public $schedule = [];

    public function mount()
    {
        $this->schedule = LoansProvider::generateAmortizationSchedule($this->loan);
    }
}
