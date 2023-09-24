<?php

namespace App\Livewire\App;

use App\Models\CapitalSubscription;
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
        return intdiv($record->payments_sum_amount, ShareCapitalProvider::PAR_VALUE);
    }

    private function amount_shares_paid($record)
    {
        return $this->number_of_shares_paid($record) * ShareCapitalProvider::PAR_VALUE;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CapitalSubscription::query())
            ->columns([
                TextColumn::make('member.full_name')
                    ->label('Name'),
                TextColumn::make('number_of_shares')
                    ->label("No. of Shares Subscribed")
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('amount_shares_subscribed')
                    ->label('Amount of Shares Subscribed')
                    ->wrapHeader()
                    ->money('PHP')
                    ->alignCenter(),
                TextColumn::make('number_of_shares_paid')
                    ->label('No. of Shares Paid')
                    ->state(fn ($record) => $this->number_of_shares_paid($record))
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('payments_sum_amount')
                    ->sum('payments', 'amount')
                    ->money('PHP')
                    ->label('Total Amount Paid-Up Capital Share Common')
                    ->wrapHeader()
                    ->alignCenter(),
                TextColumn::make('amount_receivable')
                    ->label('Subscription Receivable Common')
                    ->state(fn ($record) => $record->amount_shares_subscribed - $record->payments_sum_amount)
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
                    ->state(fn ($record) => $record->payments_sum_amount - $this->amount_shares_paid($record))
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
