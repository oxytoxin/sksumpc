<?php

namespace App\Livewire\App;

use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\TimeDeposits\ClaimTimeDeposit;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Actions\TimeDeposits\TerminateTimeDeposit;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TimeDeposit;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
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
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

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
                TextColumn::make('account_number'),
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
                        'terminated' => 'Terminated',
                    ])
                    ->default('ongoing')
                    ->query(function (Builder $query, $data) {
                        $query
                            ->when($data['value'] == 'matured', fn ($query) => $query->whereNotNull('withdrawal_date'))
                            ->when($data['value'] == 'ongoing', fn ($query) => $query->whereNull('withdrawal_date'))
                            ->when($data['value'] == 'terminated', fn ($query) => $query->whereRaw('withdrawal_date <= maturity_date'));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('certificate')
                    ->outlined()
                    ->button()
                    ->modalWidth(MaxWidth::SixExtraLarge)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalContent(fn ($record) => view('filament.app.views.time-deposit-certificate', ['time_deposit' => $record])),
                Action::make('Terminate')
                    ->form([
                        Placeholder::make('note')->content(fn ($record) => 'Pretermination will accrue only 1% interest.'),
                        DatePicker::make('withdrawal_date')
                            ->required()
                            ->default(today()),
                    ])
                    ->action(function (TimeDeposit $record, $data) {
                        app(TerminateTimeDeposit::class)->handle(timeDeposit: $record, withdrawal_date: $data['withdrawal_date']);
                        Notification::make()->title('Time deposit claimed.')->success()->send();
                    })
                    ->color(Color::Red)
                    ->visible(fn (TimeDeposit $record) => $record->transaction_date->isAfter(today()->subMonths(6)) && is_null($record->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                Action::make('claim')
                    ->form([
                        DatePicker::make('withdrawal_date')
                            ->required()
                            ->default(today()),
                    ])
                    ->action(function ($record, $data) {
                        app(ClaimTimeDeposit::class)->handle(timeDeposit: $record, withdrawal_date: $data['withdrawal_date']);
                        Notification::make()->title('Time deposit claimed.')->success()->send();
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
                        Placeholder::make('maturity_date')->content(TimeDepositsProvider::getMaturityDate(today())->format('F d, Y')),
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('time_deposits'),
                        Placeholder::make('amount')->content(fn () => format_money($record->maturity_amount, 'PHP')),
                        Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                        Placeholder::make('maturity_amount')->content(fn () => format_money(TimeDepositsProvider::getMaturityAmount($record->maturity_amount), 'PHP')),
                    ])
                    ->action(function ($record, $data) {
                        DB::beginTransaction();
                        app(ClaimTimeDeposit::class)->handle(timeDeposit: $record, withdrawal_date: $data['withdrawal_date']);
                        unset($data['withdrawal_date']);
                        TimeDeposit::create([
                            ...$data,
                            'amount' => $record->maturity_amount,
                            'maturity_amount' => TimeDepositsProvider::getMaturityAmount($record->maturity_amount),
                            'member_id' => $this->member_id,
                        ]);
                        DB::commit();
                        Notification::make()->title('Time deposit roll-overed.')->success()->send();
                    })
                    ->visible(fn (TimeDeposit $record) => $record->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                ActionGroup::make([
                    Action::make('to_savings')
                        ->form([
                            Select::make('savings_account_id')
                                ->options(SavingsAccount::whereMemberId($this->member_id)->pluck('number', 'id'))
                                ->required()
                                ->label('Account'),
                        ])
                        ->action(function ($record, $data) {
                            app(ClaimTimeDeposit::class)->handle(timeDeposit: $record, withdrawal_date: today());
                            app(DepositToSavingsAccount::class)->handle(Member::find($this->member_id), new SavingsData(
                                payment_type_id: 1,
                                reference_number: TimeDepositsProvider::FROM_TRANSFER_CODE,
                                amount: $record->maturity_amount,
                                savings_account_id: $data['savings_account_id']
                            ), TransactionType::firstWhere('name', 'CDJ'));
                            Notification::make()->title('Time deposit claimed.')->success()->send();
                        })
                        ->visible(fn (TimeDeposit $record) => $record->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                        ->icon('heroicon-o-banknotes'),
                    Action::make('to_imprests')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            app(ClaimTimeDeposit::class)->handle(timeDeposit: $record, withdrawal_date: today());
                            app(DepositToImprestAccount::class)->handle(Member::find($this->member_id), new ImprestData(
                                payment_type_id: 1,
                                reference_number: TimeDepositsProvider::FROM_TRANSFER_CODE,
                                amount: $record->maturity_amount
                            ), TransactionType::firstWhere('name', 'CDJ'));
                            Notification::make()->title('Time deposit claimed.')->success()->send();
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
                        Placeholder::make('maturity_date')->content(TimeDepositsProvider::getMaturityDate(today())->format('F d, Y')),
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('time_deposits'),
                        TextInput::make('amount')
                            ->required()
                            ->moneymask()
                            ->afterStateUpdated(fn (Set $set, $state) => $set('maturity_amount', TimeDepositsProvider::getMaturityAmount(floatval($state))))
                            ->minValue(TimeDepositsProvider::MINIMUM_DEPOSIT)->default(TimeDepositsProvider::MINIMUM_DEPOSIT),
                        Placeholder::make('number_of_days')->content(TimeDepositsProvider::NUMBER_OF_DAYS),
                        Placeholder::make('maturity_amount')->content(fn (Get $get) => format_money(TimeDepositsProvider::getMaturityAmount(floatval($get('amount'))), 'PHP')),
                    ])
                    ->action(function ($data) {
                        app(CreateTimeDeposit::class)->handle(timeDepositData: new TimeDepositData(
                            member_id: $this->member_id,
                            maturity_date: TimeDepositsProvider::getMaturityDate(today()),
                            reference_number: $data['reference_number'],
                            payment_type_id: $data['payment_type_id'],
                            amount: $data['amount'],
                            maturity_amount: TimeDepositsProvider::getMaturityAmount(floatval($data['amount'])),
                        ), transactionType: TransactionType::firstWhere('name', 'CRJ'));
                        Notification::make()->title('Time deposit created.')->success()->send();
                    })
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
        return view('livewire.app.time-deposits-table');
    }
}
