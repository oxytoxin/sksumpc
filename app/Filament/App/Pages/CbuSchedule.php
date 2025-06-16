<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Models\Gender;
use App\Models\Member;
use App\Models\MemberType;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class CbuSchedule extends Page implements HasTable, HasForms
{
    use InteractsWithTable, RequiresBookkeeperTransactionDate, InteractsWithForms;

    protected static string $view = 'filament.app.pages.share-capital';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?string $navigationLabel = 'CBU Schedule';

    protected ?string $heading = 'CBU Schedule';

    public $transaction_date;

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('transaction_date')
                    ->native(false)
                    ->live()
                    ->default(config('app.transaction_date'))
                    ->afterStateUpdated(function () {
                        $this->transaction_date = $this->form->getState()['transaction_date'];
                        $this->resetTable();
                    })
            ])
            ->columns(4);
    }

    public function updatedTableFilters($value, $key)
    {
        if ($key === 'form.transaction_date') {
            $this->transaction_date = $value;
            $this->resetTable();
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    private function number_of_shares_paid($record)
    {
        return $record->capital_subscriptions->map(fn($cs) => intdiv($cs->payments()->where('transaction_date', '<=', CarbonImmutable::make($this->tableFilters['form']['transaction_date'] ?? $this->transaction_date)->endOfDay())->sum('amount'), $cs->par_value))->sum();
    }

    private function amount_shares_paid($record)
    {
        return $record->capital_subscriptions->map(fn($cs) => intdiv($cs->payments()->where('transaction_date', '<=', CarbonImmutable::make($this->tableFilters['form']['transaction_date'] ?? $this->transaction_date)->endOfDay())->sum('amount'), $cs->par_value) * $cs->par_value)->sum();
    }

    #[Computed]
    public function Gender()
    {
        return Gender::find($this->tableFilters['gender_id']['value'])?->name;
    }

    #[Computed]
    public function MemberType()
    {
        return MemberType::find($this->tableFilters['member_type_id']['value'])?->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->has('capital_subscriptions')
                    ->withSum(['capital_subscriptions' => function ($query) {
                        $query->whereHas('payments', function ($q) {
                            $q->where('capital_subscription_payments.transaction_date', '<=', CarbonImmutable::make($this->transaction_date)->endOfDay());
                        });
                    }], 'number_of_shares')
                    ->withSum(['capital_subscriptions' => function ($query) {
                        $query->whereHas('payments', function ($q) {
                            $q->where('capital_subscription_payments.transaction_date', '<=', CarbonImmutable::make($this->transaction_date)->endOfDay());
                        });
                    }], 'amount_subscribed')
                    ->withSum(['capital_subscription_payments' => function ($query) {
                        $query->where('capital_subscription_payments.transaction_date', '<=', CarbonImmutable::make($this->transaction_date)->endOfDay());
                    }], 'amount')
                    ->orderBy('alt_full_name')
            )
            ->content(fn() => view('filament.app.views.cbu-schedule'))
            ->filters([
                SelectFilter::make('member_type_id')
                    ->relationship('member_type', 'name')
                    ->label('Member Type'),
                SelectFilter::make('gender_id')
                    ->relationship('gender', 'name')
                    ->label('Gender'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                //
            ]);
    }

    protected function getTransactionDate(): string
    {
        return $this->tableFilters['transaction_date'] ?? config('app.transaction_date');
    }
}
