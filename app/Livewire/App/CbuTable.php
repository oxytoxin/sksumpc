<?php

namespace App\Livewire\App;

use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Oxytoxin\ShareCapitalProvider;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

use function Filament\Support\format_money;

class CbuTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Member $member;

    #[On('refresh')]
    public function loanCreated()
    {
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CapitalSubscription::whereMemberId($this->member->id))
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('par_value')->money('PHP'),
                TextColumn::make('number_of_shares')
                    ->numeric()
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('number_of_terms')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('monthly_payment')->money('PHP'),
                TextColumn::make('amount_subscribed')->money('PHP')
                    ->summarize(Sum::make()->money('PHP')->label('')),
                TextColumn::make('outstanding_balance')->money('PHP')
                    ->summarize(Sum::make()->money('PHP')->label('')),
                IconColumn::make('is_common')->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ongoing' => 'On-going',
                        'paid' => 'Paid',
                    ])
                    ->query(function (Builder $query, $data) {
                        $query
                            ->when($data['value'] == 'paid', fn ($query) => $query->where('outstanding_balance', 0))
                            ->when($data['value'] == 'ongoing', fn ($query) => $query->where('outstanding_balance', '>', 0));
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                ViewAction::make()
                    ->modalContent(fn ($record) => view('filament.app.views.cbu-payments', ['cbu' => $record])),
                Action::make('Pay')
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('capital_subscription_payments'),
                        TextInput::make('amount')
                            ->required()
                            ->moneymask(),
                        TextInput::make('remarks'),
                        DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),
                    ])
                    ->action(function ($record, $data) {
                        $record->payments()->create($data);
                        Notification::make()->title('Payment made for capital subscription!')->success()->send();
                    })
                    ->visible(fn ($record) => $record->outstanding_balance > 0 && $record->payments()->exists()),
                Action::make('initial_payment')
                    ->label('Initial Payment')
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('capital_subscription_payments'),
                        TextInput::make('amount')
                            ->required()
                            ->moneymask()
                            ->default(fn ($record) => $record->initial_amount_paid)
                            ->readOnly(),
                        TextInput::make('monthly_payment')
                            ->required()
                            ->moneymask()
                            ->default(fn ($record) => $record->monthly_payment)
                            ->readOnly(),
                        TextInput::make('remarks'),
                        DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'monthly_payment' => $data['monthly_payment']
                        ]);
                        unset($data['monthly_payment']);
                        $record->payments()->create($data);
                        Notification::make()->title('Payment made for capital subscription!')->success()->send();
                    })
                    ->hidden(fn ($record) => $record->payments()->exists()),

            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn () => !$this->member->capital_subscriptions()->where('outstanding_balance', '>', 0)->exists() && auth()->user()->can('manage cbu'))
                    ->createAnother(false)
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today()),
                        Placeholder::make('number_of_terms')->content(ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS),
                        TextInput::make('number_of_shares')->numeric()->minValue(1)->default(144)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $amount_subscribed = ($state ?? 0) * ShareCapitalProvider::PAR_VALUE + ($get('initial_amount_paid') ?? 0);
                                $monthly_payment = ($amount_subscribed - ($get('initial_amount_paid') ?? 0)) / ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS;
                                $set('amount_subscribed', $amount_subscribed);
                                $set('monthly_payment', $monthly_payment);
                            }),
                        TextInput::make('initial_amount_paid')
                            ->required()
                            ->moneymask()
                            ->default($this->member->member_type->minimum_initial_payment)
                            ->afterStateUpdated(function ($set, $get, $record, $state) {
                                $amount_subscribed = ($get('monthly_payment') ?? 0) * ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS + ($get('initial_amount_paid') ?? 0);
                                $set('amount_subscribed', $amount_subscribed);
                            }),
                        TextInput::make('monthly_payment')
                            ->required()
                            ->moneymask()
                            ->afterStateUpdated(function ($set, $get, $record, $state) {
                                $amount_subscribed = ($state ?? 0) * ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS + ($get('initial_amount_paid') ?? 0);
                                $number_of_shares = $amount_subscribed / ShareCapitalProvider::PAR_VALUE;
                                $set('amount_subscribed', $amount_subscribed);
                                $set('number_of_shares', $number_of_shares);
                            }),
                        TextInput::make('amount_subscribed')
                            ->default(72000)
                            ->moneymask()
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $number_of_shares = ($state ?? 0) / ShareCapitalProvider::PAR_VALUE;
                                $monthly_payment = (($state ?? 0) - ($get('initial_amount_paid') ?? 0)) / ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS;
                                $set('monthly_payment', $monthly_payment);
                                $set('number_of_shares', $number_of_shares);
                            }),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $this->member->capital_subscriptions()->update([
                            'is_common' => false
                        ]);
                        $cbu = $this->member->capital_subscriptions()->create([
                            ...$data,
                            'number_of_terms' => ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS,
                            'par_value' => ShareCapitalProvider::PAR_VALUE,
                            'is_common' => true,
                            'code' => Str::random(12),
                        ]);
                        DB::commit();
                        Notification::make()->title('Capital subscription created!')->success()->send();
                    }),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.cbu-subsidiary-ledger', ['member' => $this->member]))
            ])
            ->bulkActions([]);
    }

    public function render(): View
    {
        return view('livewire.app.cbu-table');
    }
}
