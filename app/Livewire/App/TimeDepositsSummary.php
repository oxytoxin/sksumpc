<?php

namespace App\Livewire\App;

use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\TimeDeposit;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;

class TimeDepositsSummary extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(TimeDeposit::query())
            ->columns([
                TextColumn::make('member.full_name')->label('Member'),
                TextColumn::make('tdc_number')->label('TDC Number'),
                TextColumn::make('maturity_date')->date('m/d/Y'),
                TextColumn::make('maturity_amount')->money('PHP')->label('Value Upon Maturity')->summarize(Sum::make()->label('')->money('PHP')),
                TextColumn::make('interest')->money('PHP')->summarize(Sum::make()->label('')->money('PHP')),
                TextColumn::make('amount')->money('PHP')->label('Principal')->summarize(Sum::make()->label('')->money('PHP')),
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
        return view('livewire.app.time-deposits-summary');
    }
}
