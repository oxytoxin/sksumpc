<?php

namespace App\Livewire\App;

use App\Models\Member;
use Filament\Tables;
use App\Models\Saving;
use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use App\Oxytoxin\SavingsData;
use App\Oxytoxin\SavingsProvider;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\RawJs;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Validation\ValidationException;

use function Filament\Support\format_money;

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
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('reference_number'),
                TextColumn::make('withdrawal')->label('Withdrawal')->money('PHP'),
                TextColumn::make('deposit')->label('Deposit/Interest')->money('PHP'),
                TextColumn::make('number_of_days'),
                TextColumn::make('balance')->money('PHP'),
                TextColumn::make('interest')->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->headerActions([
                CreateAction::make('Deposit')
                    ->label('Deposit')
                    ->modalHeading('Deposit Savings')
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today()),
                        TextInput::make('reference_number')->required()
                            ->unique('savings'),
                        TextInput::make('amount')->prefix('PHP')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $member =  Member::find($this->member_id);
                        SavingsProvider::createSavings($member, (new SavingsData(...$data)));
                        DB::commit();
                    })
                    ->createAnother(false),
                CreateAction::make('Withdraw')
                    ->label('Withdraw')
                    ->modalHeading('Withdraw Savings')
                    ->color(Color::Red)
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today()),
                        TextInput::make('reference_number')->required()
                            ->unique('savings'),
                        TextInput::make('amount')->prefix('PHP')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        $data['amount'] = $data['amount'] * -1;
                        DB::beginTransaction();
                        $member =  Member::find($this->member_id);
                        SavingsProvider::createSavings($member, (new SavingsData(...$data)));
                        DB::commit();
                    })
                    ->createAnother(false),
                CreateAction::make('to_imprests')
                    ->label('Transfer to Imprests')
                    ->modalHeading('Transfer to Imprests')
                    ->color(Color::Amber)
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today()),
                        TextInput::make('amount')->prefix('PHP')
                            ->required()
                            ->mask(fn ($state) => RawJs::make('$money'))
                            ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                            ->prefix('P')
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $member =  Member::find($this->member_id);
                        $data['reference_number'] = '#TRANSFERFROMSAVINGS';
                        ImprestsProvider::createImprest($member, (new ImprestData(...$data)));
                        $data['amount'] = $data['amount'] * -1;
                        $data['reference_number'] = '#TRANSFERTOIMPRESTS';
                        SavingsProvider::createSavings($member, (new SavingsData(...$data)));
                        DB::commit();
                    })
                    ->createAnother(false),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.savings-subsidiary-ledger', ['member' => $this->member_id]))
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
