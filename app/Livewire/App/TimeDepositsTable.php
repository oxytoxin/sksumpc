<?php

namespace App\Livewire\App;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\TimeDeposits\ClaimTimeDeposit;
use App\Actions\TimeDeposits\RolloverTimeDeposit;
use App\Actions\TimeDeposits\TerminateTimeDeposit;
use App\Enums\MsoType;
use App\Models\Loan;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TimeDeposit;
use App\Models\TimeDepositAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use Auth;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Number;

class TimeDepositsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $member_id;

    public function table(Table $table): Table
    {
        return $table
            ->query(TimeDepositAccount::whereMemberId($this->member_id))
            ->columns([
                TextColumn::make('number')->label('Account Number'),
                TextColumn::make('time_deposit.tdc_number')->label('TDC Number'),
                TextColumn::make('time_deposit.amount')->money('PHP')->label('Principal'),
                TextColumn::make('time_deposit.interest')->money('PHP'),
                TextColumn::make('time_deposit.transaction_date')->date('m/d/Y'),
                TextColumn::make('time_deposit.maturity_date')->date('m/d/Y'),
                TextColumn::make('time_deposit.maturity_amount')->money('PHP')->label('Value Upon Maturity'),
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
                            ->when($data['value'] == 'matured', fn($query) => $query->whereRelation('time_deposit', 'withdrawal_date', '!=', null))
                            ->when($data['value'] == 'ongoing', fn($query) => $query->whereRelation('time_deposit', 'withdrawal_date', null))
                            ->when($data['value'] == 'terminated', fn($query) => $query->whereHas('time_deposit', fn($query) => $query->whereRaw('withdrawal_date <= maturity_date')));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('Terminate')
                    ->form([
                        Placeholder::make('note')->content(fn($record) => 'Pretermination will accrue only 1% interest. Interest accrued: ' . Number::currency($record->time_deposit->accrued_interest, 'PHP')),
                    ])->visible(Auth::user()->can('manage mso'))
                    ->action(function (TimeDepositAccount $record, $data) {
                        app(TerminateTimeDeposit::class)->handle(time_deposit: $record->time_deposit);
                        Notification::make()->title('Time deposit claimed.')->success()->send();
                    })
                    ->color(Color::Red)
                    ->visible(fn(TimeDepositAccount $record) => (config('app.transaction_date') ?? today())->isBefore($record->time_deposit->maturity_date) && is_null($record->time_deposit->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                Action::make('claim')
                    ->requiresConfirmation()
                    ->visible(Auth::user()->can('manage mso'))
                    ->action(function ($record, $data) {
                        app(ClaimTimeDeposit::class)->handle(time_deposit: $record->time_deposit);
                        Notification::make()->title('Time deposit claimed.')->success()->send();
                    })
                    ->visible(fn(TimeDepositAccount $record) => $record->time_deposit->maturity_date->isBefore(today()) && is_null($record->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                Action::make('rollover')
                    ->visible(Auth::user()->can('manage mso'))
                    ->form(fn(TimeDepositAccount $record) => [
                        TextInput::make('number_of_days')->minValue(1)->reactive()->default($record->time_deposit->number_of_days),
                        TextInput::make('interest_rate')->minValue(0.01)->reactive()->default(round($record->time_deposit->interest_rate * 100, 2))->dehydrateStateUsing(fn($state) => round($state / 100, 4)),
                        Placeholder::make('maturity_date')->content(TimeDepositsProvider::getMaturityDate(today())->format('F d, Y')),
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->default($record->time_deposit->reference_number),
                        Placeholder::make('amount')->content(fn() => Number::currency($record->time_deposit->maturity_amount, 'PHP')),
                        Placeholder::make('maturity_amount')->content(fn($record) => Number::currency(TimeDepositsProvider::getMaturityAmount($record->time_deposit->maturity_amount), 'PHP')),
                    ])
                    ->action(function ($record, $data) {
                        app(RolloverTimeDeposit::class)->handle(
                            time_deposit: $record->time_deposit,
                            interest_rate: $data['interest_rate'],
                            number_of_days: $data['number_of_days'],
                            reference_number: $data['reference_number'],
                            payment_type_id: $data['payment_type_id'],
                        );
                        Notification::make()->title('Time deposit roll-overed.')->success()->send();
                    })
                    ->visible(fn(TimeDepositAccount $record) => $record->time_deposit->maturity_date->isBefore(today()) && is_null($record->time_deposit->withdrawal_date))
                    ->icon('heroicon-o-banknotes')
                    ->button(),
                ActionGroup::make([
                    Action::make('certificate')
                        ->modalWidth(MaxWidth::SixExtraLarge)
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)->visible(Auth::user()->can('manage mso'))
                        ->modalContent(fn($record) => view('filament.app.views.time-deposit-certificate', ['time_deposit' => $record->time_deposit])),
                    Action::make('sl')
                        ->label('Subsidiary Ledger')
                        ->url(fn($record) => route('filament.app.resources.members.time-deposit-subsidiary-ledger', ['time_deposit_account' => $record])),
                ])
                    ->button()
                    ->outlined()
                    ->icon(false)
                    ->label('View'),
            ])
            ->headerActions([])
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
