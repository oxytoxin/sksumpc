<?php

namespace App\Livewire\App;

use App\Models\Imprest;
use App\Models\Member;
use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use Carbon\Carbon;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ImprestsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $member_id;

    public function table(Table $table): Table
    {
        return $table
            ->query(Imprest::whereMemberId($this->member_id))
            ->recordClasses(fn ($record) => $record->amount > 0 ? 'bg-green-200' : 'bg-red-200')
            ->columns([
                TextColumn::make('transaction_date')->date('m/d/Y'),
                TextColumn::make('amount')->money('PHP'),
                TextColumn::make('reference_number'),
                TextColumn::make('number_of_days'),
                TextColumn::make('balance')->money('PHP'),
                TextColumn::make('interest')->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make('Deposit')
                    ->label('Deposit')
                    ->modalHeading('Deposit Imprest')
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today()),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('amount')->prefix('PHP')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $member =  Member::find($this->member_id);
                        ImprestsProvider::createImprest($member, (new ImprestData(...$data)));
                        DB::commit();
                    })
                    ->createAnother(false),
                CreateAction::make('Withdraw')
                    ->label('Withdraw')
                    ->modalHeading('Withdraw Imprest')
                    ->color(Color::Red)
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today()),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('amount')->prefix('PHP')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        $data['amount'] = $data['amount'] * -1;
                        DB::beginTransaction();
                        $member =  Member::find($this->member_id);
                        ImprestsProvider::createImprest($member, (new ImprestData(...$data)));
                        DB::commit();
                    })
                    ->createAnother(false),
            ])
            ->actions([
                DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.imprests-table');
    }
}
