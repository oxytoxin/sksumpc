<?php

namespace App\Livewire\App;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use App\Actions\CapitalSubscription\CreateNewCapitalSubscription;
use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionData;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class CbuTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Member $member;

    #[On('refresh')]
    public function loanCreated() {}

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
                IconColumn::make('is_active')->boolean(),
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
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ActionGroup::make([
                    Action::make('payments')
                        ->label('Payments')
                        ->icon('heroicon-s-eye')
                        ->modalContent(fn ($record) => view('filament.app.views.cbu-payments', ['cbu' => $record])),
                    ViewAction::make()
                        ->label('Amortization Schedule')
                        ->url(fn ($record) => route('filament.app.resources.members.cbu-amortization-schedule', ['cbu' => $record])),
                    Action::make('share_certificate')
                        ->icon('heroicon-s-eye')
                        ->url(fn ($record) => route('filament.app.resources.capital-subscriptions.share-certificate', ['capital_subscription' => $record]), true),
                ]),

            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn () => ! $this->member->capital_subscriptions()->where('outstanding_balance', '>', 0)->exists() && auth()->user()->can('manage cbu'))
                    ->createAnother(false)
                    ->schema([
                        Placeholder::make('number_of_terms')->content($this->member->member_type->additional_number_of_terms),
                        TextInput::make('number_of_shares')->numeric()->minValue(1)->default(144)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $amount_subscribed = ($state ?? 0) * $this->member->member_type->par_value + ($get('initial_amount_paid') ?? 0);
                                $monthly_payment = ($amount_subscribed - ($get('initial_amount_paid') ?? 0)) / $this->member->member_type->additional_number_of_terms;
                                $set('amount_subscribed', $amount_subscribed);
                                $set('monthly_payment', $monthly_payment);
                            }),
                        TextInput::make('initial_amount_paid')
                            ->required()
                            ->moneymask()
                            ->default($this->member->member_type->minimum_initial_payment)
                            ->afterStateUpdated(function ($set, $get) {
                                $amount_subscribed = ($get('monthly_payment') ?? 0) * $this->member->member_type->additional_number_of_terms + ($get('initial_amount_paid') ?? 0);
                                $set('amount_subscribed', $amount_subscribed);
                            }),
                        TextInput::make('monthly_payment')
                            ->required()
                            ->moneymask()
                            ->default((72000 - $this->member->member_type->minimum_initial_payment) / $this->member->member_type->additional_number_of_terms)
                            ->afterStateUpdated(function ($set, $get, $state) {
                                $amount_subscribed = ($state ?? 0) * $this->member->member_type->additional_number_of_terms + ($get('initial_amount_paid') ?? 0);
                                $number_of_shares = $amount_subscribed / $this->member->member_type->par_value;
                                $set('amount_subscribed', $amount_subscribed);
                                $set('number_of_shares', $number_of_shares);
                            }),
                        TextInput::make('amount_subscribed')
                            ->default(72000)
                            ->moneymask()
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $number_of_shares = ($state ?? 0) / $this->member->member_type->par_value;
                                $monthly_payment = (($state ?? 0) - ($get('initial_amount_paid') ?? 0)) / $this->member->member_type->additional_number_of_terms;
                                $set('monthly_payment', $monthly_payment);
                                $set('number_of_shares', $number_of_shares);
                            }),
                    ])
                    ->action(function ($data) {
                        $cbu_data = new CapitalSubscriptionData(
                            number_of_terms: $this->member->member_type->additional_number_of_terms,
                            number_of_shares: $data['number_of_shares'],
                            initial_amount_paid: $data['initial_amount_paid'],
                            monthly_payment: $data['monthly_payment'],
                            amount_subscribed: $data['amount_subscribed'],
                            par_value: $this->member->member_type->par_value,
                            is_active: true,
                            code: Str::random(12)
                        );
                        app(CreateNewCapitalSubscription::class)->handle($this->member, $cbu_data);
                        Notification::make()->title('Capital subscription created!')->success()->send();
                    }),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.cbu-subsidiary-ledger', ['member' => $this->member])),
            ])
            ->toolbarActions([]);
    }

    public function mount()
    {
        $this->member->load('member_type');
    }

    public function render(): View
    {
        return view('livewire.app.cbu-table');
    }
}
