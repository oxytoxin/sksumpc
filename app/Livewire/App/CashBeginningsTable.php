<?php

namespace App\Livewire\App;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use App\Models\CashBeginning;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CashBeginningsTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
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
                    ->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        DatePicker::make('transaction_date')
                            ->default(today())
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->moneymask(),
                    ]),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->schema([
                        DatePicker::make('transaction_date')
                            ->default(today())
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->moneymask(),
                        Hidden::make('cashier_id')->default(auth()->id()),
                    ])
                    ->createAnother(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.cash-beginnings-table');
    }
}
