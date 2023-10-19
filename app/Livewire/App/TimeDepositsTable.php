<?php

namespace App\Livewire\App;

use App\Models\Member;
use App\Models\TimeDeposit;
use App\Oxytoxin\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use App\Oxytoxin\SavingsData;
use App\Oxytoxin\SavingsProvider;
use App\Oxytoxin\TimeDepositsProvider;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

use function Filament\Support\format_money;

class TimeDepositsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $member_id;

    public function table(Table $table): Table
    {
        return $table
            ->query(TimeDeposit::whereMemberId($this->member_id))
            ->columns([
                TextColumn::make('tdc_number')->label('TDC Number'),
                TextColumn::make('amount')->money('PHP')->label('Principal'),
                TextColumn::make('interest')->money('PHP'),
                TextColumn::make('maturity_date')->date('m/d/Y'),
                TextColumn::make('maturity_amount')->money('PHP')->label('Value Upon Maturity'),
                TextColumn::make('withdrawal_date')->date('m/d/Y'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ongoing' => 'On-going',
                        'matured' => 'Matured',
                    ])
                    ->default('ongoing')
                    ->query(function (Builder $query, $data) {
                        $query
                            ->when($data['value'] == 'matured', fn ($query) => $query->whereNotNull('withdrawal_date'))
                            ->when($data['value'] == 'ongoing', fn ($query) => $query->whereNull('withdrawal_date'));
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('claim')
                    ->form([
                        DatePicker::make('withdrawal_date')
                            ->required()
                            ->default(today()),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'withdrawal_date' => $data['withdrawal_date']
                        ]);
                        Notification::make()->title('Time deposite claimed.')->success()->send();
                    })
                    ->visible(fn (TimeDeposit $record) => $record->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                Action::make('rollover')
                    ->form(fn (TimeDeposit $record) => [
                        DatePicker::make('withdrawal_date')
                            ->required()
                            ->native(false)
                            ->default(today()),
                        DatePicker::make('transaction_date')->label('Roll-over date')->required()->default(today())->native(false)->live()->afterStateUpdated(fn (Set $set, $state) => $set('maturity_date', TimeDepositsProvider::getMaturityDate($state))),
                        DatePicker::make('maturity_date')->required()->readOnly()->default(TimeDepositsProvider::getMaturityDate(today()))->native(false),
                        TextInput::make('reference_number')->required()
                            ->unique('time_deposits'),
                        Placeholder::make('amount')->content(fn () => format_money($record->maturity_amount, 'PHP')),
                        Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                        Placeholder::make('maturity_amount')->content(fn () => format_money(TimeDepositsProvider::getMaturityAmount($record->maturity_amount), 'PHP')),
                        TextInput::make('tdc_number')->label('TDC Number')->required()->unique('time_deposits', 'tdc_number')->validationAttribute('TDC Number'),
                    ])
                    ->action(function ($record, $data) {
                        DB::beginTransaction();
                        $record->update([
                            'withdrawal_date' => $data['withdrawal_date']
                        ]);
                        unset($data['withdrawal_date']);
                        TimeDeposit::create([
                            ...$data,
                            'amount' => $record->maturity_amount,
                            'maturity_amount' => TimeDepositsProvider::getMaturityAmount($record->maturity_amount),
                            'member_id' => $this->member_id,
                        ]);
                        DB::commit();
                        Notification::make()->title('Time deposite roll-overed.')->success()->send();
                    })
                    ->visible(fn (TimeDeposit $record) => $record->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                ActionGroup::make([
                    Action::make('to_savings')
                        ->form([
                            DatePicker::make('withdrawal_date')
                                ->required()
                                ->default(today()),
                        ])
                        ->action(function ($record, $data) {
                            $record->update([
                                'withdrawal_date' => $data['withdrawal_date']
                            ]);
                            SavingsProvider::createSavings(Member::find($this->member_id), (new SavingsData($data['withdrawal_date'], '#FROMTIMEDEPOSITS', $record->maturity_amount)));
                            Notification::make()->title('Time deposite claimed.')->success()->send();
                        })
                        ->visible(fn (TimeDeposit $record) => $record->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                        ->icon('heroicon-o-banknotes'),
                    Action::make('to_imprests')
                        ->form([
                            DatePicker::make('withdrawal_date')
                                ->required()
                                ->default(today()),
                        ])
                        ->action(function ($record, $data) {
                            $record->update([
                                'withdrawal_date' => $data['withdrawal_date']
                            ]);
                            ImprestsProvider::createImprest(Member::find($this->member_id), (new ImprestData($data['withdrawal_date'], '#FROMTIMEDEPOSITS', $record->maturity_amount)));
                            Notification::make()->title('Time deposite claimed.')->success()->send();
                        })
                        ->visible(fn (TimeDeposit $record) => $record->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                        ->icon('heroicon-o-banknotes'),
                ])
                    ->button()
                    ->icon(false)
                    ->label('Transfer'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        DatePicker::make('transaction_date')->required()->default(today())->native(false)->live()->afterStateUpdated(fn (Set $set, $state) => $set('maturity_date', TimeDepositsProvider::getMaturityDate($state))),
                        DatePicker::make('maturity_date')->required()->readOnly()->default(TimeDepositsProvider::getMaturityDate(today()))->native(false),
                        TextInput::make('reference_number')->required()
                            ->unique('time_deposits'),
                        TextInput::make('amount')->prefix('PHP')
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(fn (Set $set, $state) => $set('maturity_amount', TimeDepositsProvider::getMaturityAmount(floatval($state))))
                            ->numeric()
                            ->minValue(TimeDepositsProvider::MINIMUM_DEPOSIT)->default(TimeDepositsProvider::MINIMUM_DEPOSIT),
                        Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                        Placeholder::make('maturity_amount')->content(fn (Get $get) => format_money(TimeDepositsProvider::getMaturityAmount(floatval($get('amount'))), 'PHP')),
                        TextInput::make('tdc_number')->label('TDC Number')->required()->unique('time_deposits', 'tdc_number')->validationAttribute('TDC Number'),
                    ])
                    ->action(function ($data) {
                        TimeDeposit::create([
                            ...$data,
                            'member_id' => $this->member_id,
                        ]);
                    })
                    ->createAnother(false)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.time-deposits-table');
    }
}
