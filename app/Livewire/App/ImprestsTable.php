<?php

namespace App\Livewire\App;

use App\Models\Imprest;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImprestsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $member_id;

    public function table(Table $table): Table
    {
        return $table
            ->query(Imprest::whereMemberId($this->member_id))
            ->recordclasses(fn ($record) => $record->amount > 0 ? 'bg-green-200' : 'bg-red-200')
            ->columns([
                TextColumn::make('transaction_date')->date('m/d/Y'),
                TextColumn::make('reference_number'),
                TextColumn::make('withdrawal')->label('Withdrawal')->money('PHP'),
                TextColumn::make('deposit')->label('Deposit/Interest')->money('PHP'),
                TextColumn::make('balance')->money('PHP'),
                TextColumn::make('days_till_next_transaction'),
                TextColumn::make('interest')->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.imprest-subsidiary-ledger', ['member' => $this->member_id])),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.imprests-table');
    }
}
