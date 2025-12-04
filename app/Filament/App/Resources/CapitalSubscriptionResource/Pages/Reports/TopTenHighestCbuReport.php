<?php

namespace App\Filament\App\Resources\CapitalSubscriptionResource\Pages\Reports;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\CapitalSubscriptionResource;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\SignatureSet;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;

class TopTenHighestCbuReport extends Page implements HasForms
{
    use HasSignatories, InteractsWithForms;

    protected static string $resource = CapitalSubscriptionResource::class;

    protected static string $view = 'filament.app.resources.capital-subscription-resource.pages.reports.top-ten-highest-cbu-report';

    protected ?string $heading = 'Top 10 Highest CBU Contributor';

    public $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_type_id')
                    ->options(MemberType::pluck('name', 'id'))
                    ->label('Member Type')
                    ->live(),
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
    public function contributors()
    {
        return Member::whereHas('capital_subscription_payments', fn ($query) => $query->whereDate('capital_subscription_payments.transaction_date', '<=', date_create(Carbon::create(month: $this->data['month'], year: $this->data['year'])->endOfMonth())))
            ->when($this->data['member_type_id'] ?? false, fn ($query, $member_type_id) => $query->where('member_type_id', $member_type_id))
            ->withSum([
                'capital_subscription_payments' => fn ($query) => $query->whereDate('capital_subscription_payments.transaction_date', '<=', date_create(Carbon::create(month: $this->data['month'], year: $this->data['year'])->endOfMonth())),
            ], 'amount')
            ->orderBy('capital_subscription_payments_sum_amount', 'desc')
            ->limit(10)
            ->get();
    }

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'CBU Reports')->first();
    }
}
