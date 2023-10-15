<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Models\Member;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use App\Models\CapitalSubscriptionPayment;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Oxytoxin\LoansProvider;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

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
        return 'Loan Subsidiary Ledger for ' . $this->loan->member->full_name;
    }
}
