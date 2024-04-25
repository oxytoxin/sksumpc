<?php

namespace App\Filament\App\Pages;

use App\Models\CapitalSubscriptionPayment;
use App\Models\MemberType;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Computed;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class CbuScheduleSummary extends Page
{
    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?string $navigationLabel = 'CBU Schedule Summary';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.app.pages.cbu-schedule-summary';

    public $data = [];

    public function getHeading(): string|Htmlable
    {
        return 'CBU Schedule Summary';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    private function getAmounts($amount_paid, MemberType $memberType)
    {
        return [
            'shares_paid' => intdiv($amount_paid, $memberType->par_value) * $memberType->par_value,
            'shares_deposit' => $amount_paid - intdiv($amount_paid, $memberType->par_value) * $memberType->par_value,
            'amount_paid' => $amount_paid,
        ];
    }

    #[Computed]
    public function DateRange()
    {
        return implode(' - ', collect(explode(' - ', $this->data['transaction_date']))->map(fn ($d) => date_create($d)->format('F d, Y'))->toArray());
    }

    #[Computed]
    public function laboratoryAmounts()
    {
        $memberType = MemberType::find(4);
        $amount_paid = CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereRelation('member', 'member_type_id', 4);
        })->when($this->data['transaction_date'], fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))
            ->sum('amount');

        return $this->getAmounts($amount_paid, $memberType);
    }

    #[Computed]
    public function regularAmounts()
    {
        $memberType = MemberType::find(1);
        $amount_paid = CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereHas('member', fn ($qu) => $qu->where('member_type_id', 1));
        })
            ->when($this->data['transaction_date'], fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))
            ->sum('amount');
        $amounts1 = $this->getAmounts($amount_paid, $memberType);
        $memberType = MemberType::find(2);
        $amount_paid = CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereHas('member', fn ($qu) => $qu->where('member_type_id', 2));
        })->when($this->data['transaction_date'], fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))
            ->sum('amount');
        $amounts2 = $this->getAmounts($amount_paid, $memberType);

        return [
            'shares_paid' => $amounts1['shares_paid'] + $amounts2['shares_paid'],
            'shares_deposit' => $amounts1['shares_deposit'] + $amounts2['shares_deposit'],
            'amount_paid' => $amounts1['amount_paid'] + $amounts2['amount_paid'],
        ];
    }

    #[Computed]
    public function associateAmounts()
    {
        $memberType = MemberType::find(3);
        $amount_paid = CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereRelation('member', 'member_type_id', 3);
        })
            ->when($this->data['transaction_date'], fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))
            ->sum('amount');

        return $this->getAmounts($amount_paid, $memberType);
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DateRangePicker::make('transaction_date')
                ->format('m/d/Y')
                ->displayFormat('MM/DD/YYYY')
        ])->columns(4)->statePath('data');
    }
}
