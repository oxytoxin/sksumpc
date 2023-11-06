<?php

namespace App\Livewire\App;

use App\Models\CashBeginning;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CashBeginningsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(CashBeginning::query()->latest())
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date('m/d/Y'),
                TextColumn::make('amount')
                    ->money('PHP')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        DatePicker::make('transaction_date')
                            ->default(today())
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->moneymask(),
                    ]),
                DeleteAction::make()
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        DatePicker::make('transaction_date')
                            ->default(today())
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->moneymask(),
                        Hidden::make('cashier_id')->default(auth()->id())
                    ])
                    ->createAnother(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.cash-beginnings-table');
    }
}
