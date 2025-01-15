<?php

namespace App\Filament\App\Pages;

use App\Enums\MemberTypes;
use App\Models\Account;
use App\Models\CapitalSubscriptionPayment;
use App\Models\MemberType;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
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

    public $transaction_date;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Schedule Summary';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage cbu');
    }

    private function getAmounts(MemberTypes $memberType)
    {
        $transaction_date = CarbonImmutable::parse($this->transaction_date ?? today()->format('M d, Y'))->endOfDay();
        $query = match ($memberType) {
            MemberTypes::REGULAR => Account::whereTag('member_regular_cbu_paid'),
            MemberTypes::ASSOCIATE => Account::whereTag('member_preferred_cbu_paid'),
            MemberTypes::LABORATORY => Account::whereTag('member_laboratory_cbu_paid'),
        };
        $account = $query
            ->withSum(['recursiveTransactions as debit' => fn($q) => $q->where('transaction_date', '<=', $transaction_date)], 'debit')
            ->withSum(['recursiveTransactions as credit' => fn($q) => $q->where('transaction_date', '<=', $transaction_date)], 'credit')
            ->first();

        return [
            'shares_paid' => round($account->credit - $account->debit, 2),
            'shares_deposit' => round($account->debit, 2),
            'amount_paid' => round($account->credit, 2),
        ];
    }

    #[Computed]
    public function laboratoryAmounts()
    {
        return $this->getAmounts(MemberTypes::LABORATORY);
    }

    #[Computed]
    public function regularAmounts()
    {

        return $this->getAmounts(MemberTypes::REGULAR);
    }

    #[Computed]
    public function associateAmounts()
    {
        return $this->getAmounts(MemberTypes::ASSOCIATE);
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('transaction_date')
                ->native(false)
                ->live()
                ->default(config('app.transaction_date'))

        ])->columns(4);
    }
}
