<?php

namespace App\Livewire\App;

use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Oxytoxin\ShareCapitalProvider;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CbuSummary extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    private function number_of_shares_paid($record)
    {
        return $record->capital_subscriptions->map(fn ($cs) => intdiv($cs->payments()->sum('amount'), $cs->par_value))->sum();
    }

    private function amount_shares_paid($record)
    {
        return $record->capital_subscriptions->map(fn ($cs) => intdiv($cs->payments()->sum('amount'), $cs->par_value) * $cs->par_value)->sum();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->withSum('capital_subscriptions', 'number_of_shares')
                    ->withSum('capital_subscriptions', 'amount_subscribed')
                    ->withSum('capital_subscription_payments', 'amount')
            )
            ->columns([
                TextColumn::make('full_name')
                    ->searchable()
                    ->label('Name'),
                TextColumn::make('capital_subscriptions_sum_number_of_shares')
                    ->label("No. of Shares Subscribed")
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('capital_subscriptions_sum_amount_subscribed')
                    ->label('Amount of Shares Subscribed')
                    ->wrapHeader()
                    ->money('PHP')
                    ->alignCenter(),
                TextColumn::make('number_of_shares_paid')
                    ->label('No. of Shares Paid')
                    ->state(fn ($record) => $this->number_of_shares_paid($record))
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('capital_subscription_payments_sum_amount')
                    ->money('PHP')
                    ->label('Total Amount Paid-Up Capital Share Common')
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('amount_receivable')
                    ->label('Subscription Receivable Common')
                    ->state(fn ($record) => $record->capital_subscriptions_sum_amount_subscribed - $record->capital_subscription_payments_sum_amount)
                    ->money('PHP')
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('amount_shares_paid')
                    ->label('Paid-Up Share Capital Common')
                    ->state(fn ($record) => $this->amount_shares_paid($record))
                    ->money('PHP')
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('amount_shares_deposit')
                    ->label('Deposit for Share Capital Subscription')
                    ->state(fn ($record) => $record->capital_subscription_payments_sum_amount - $this->amount_shares_paid($record))
                    ->money('PHP')
                    ->wrapHeader()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.cbu-summary');
    }
}
