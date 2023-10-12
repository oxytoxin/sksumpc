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
                TextColumn::make('number_of_shares')
                    ->numeric()
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('number_of_terms')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('amount_subscribed')->money('PHP')
                    ->summarize(Sum::make()->money('PHP')->label('')),
                TextColumn::make('initial_amount_paid')->money('PHP')
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
                        Select::make('type')
                            ->options([
                                'OR' => 'OR',
                                'JV' => 'JV',
                                'CV' => 'CV',
                            ])
                            ->default('OR')
                            ->selectablePlaceholder(false)
                            ->live()
                            ->required(),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('amount')->required()->numeric()->minValue(1)->prefix('P'),
                        TextInput::make('remarks'),
                        DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),
                    ])
                    ->action(function ($record, $data) {
                        $record->payments()->create($data);
                        Notification::make()->title('Payment made for capital subscription!')->success()->send();
                    })
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn () => !$this->member->capital_subscriptions()->where('outstanding_balance', '>', 0)->exists())
                    ->createAnother(false)
                    ->form([
                        Placeholder::make('number_of_terms')->content(ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS),
                        TextInput::make('number_of_shares')->numeric()->minValue(1)->default(144)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $data = ShareCapitalProvider::fromNumberOfShares($state, ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS);
                                $set('amount_subscribed', $data['amount_subscribed']);
                                $set('monthly_payment', $data['monthly_payment']);
                            }),
                        TextInput::make('amount_subscribed')->prefix('P')->numeric()->minValue(1)->default(72000)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $data = ShareCapitalProvider::fromAmountSubscribed($state, ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS);
                                $set('monthly_payment', $data['monthly_payment']);
                                $set('number_of_shares', $data['number_of_shares']);
                            }),
                        TextInput::make('monthly_payment')->prefix('P')->numeric()->minValue(1)->default(2000)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $data = ShareCapitalProvider::fromMonthlyPayment($state, ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS);
                                $set('amount_subscribed', $data['amount_subscribed']);
                                $set('number_of_shares', $data['number_of_shares']);
                            }),
                        TextInput::make('initial_amount_paid')->prefix('P')->numeric()->minValue(1)->default(2000),
                    ])
                    ->action(function ($data) {
                        unset($data['monthly_payment']);
                        DB::beginTransaction();
                        $this->member->capital_subscriptions()->update([
                            'is_common' => false
                        ]);
                        $cbu = $this->member->capital_subscriptions()->create([
                            ...$data,
                            'par_value' => ShareCapitalProvider::PAR_VALUE,
                            'is_common' => true,
                            'code' => Str::random(12),
                        ]);
                        $cbu->payments()->create([
                            'amount' => $cbu->initial_amount_paid,
                            'reference_number' => '#INITIALAMOUNTPAID',
                            'type' => 'OR',
                            'transaction_date' => $cbu->transaction_date
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
