<?php

namespace App\Livewire\App;

use App\Models\Saving;
use App\Models\SavingsAccount;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SavingsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $member_id;

    public function table(Table $table): Table
    {
        return $table
            ->query(Saving::whereMemberId($this->member_id))
            ->recordClasses(fn ($record) => $record->amount > 0 ? 'bg-green-200' : 'bg-red-200')
            ->columns([
                TextColumn::make('savings_account.number'),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('reference_number'),
                TextColumn::make('withdrawal')->label('Withdrawal')->money('PHP'),
                TextColumn::make('deposit')->label('Deposit/Interest')->money('PHP'),
                TextColumn::make('balance')->money('PHP'),
                TextColumn::make('days_till_next_transaction'),
                TextColumn::make('interest')->money('PHP'),
            ])
            ->filters([
                SelectFilter::make('savings_account_id')
                    ->options(SavingsAccount::whereMemberId($this->member_id)->pluck('number', 'id'))
                    ->default(SavingsAccount::whereMemberId($this->member_id)->first()?->id)
                    ->label('Account'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                DeleteAction::make(),
            ])
            ->headerActions([
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->visible(fn ($livewire) => filled($livewire->tableFilters['savings_account_id']['value']))
                    ->url(fn ($livewire) => route('filament.app.resources.members.savings-subsidiary-ledger', ['savings_account' => $livewire->tableFilters['savings_account_id']['value']])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.savings-table');
    }
}
