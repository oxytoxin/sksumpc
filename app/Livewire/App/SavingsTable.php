<?php

namespace App\Livewire\App;

use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\SavingsProvider;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
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
                    ->label('Account'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                DeleteAction::make()
            ])
            ->headerActions([
                CreateAction::make('NewAccount')
                    ->label('New Account')
                    ->modalHeading('New Savings Account')
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('number')
                            ->required(),
                    ])
                    ->action(function ($data) {
                        SavingsAccount::create([
                            'member_id' => $this->member_id,
                            ...$data,
                        ]);
                    })
                    ->color(Color::Emerald)
                    ->createAnother(false),
                ActionGroup::make([
                    CreateAction::make('Deposit')
                        ->label('Deposit')
                        ->modalHeading('Deposit Savings')
                        ->form([
                            Select::make('payment_type_id')
                                ->paymenttype()
                                ->required(),
                            TextInput::make('reference_number')->required()
                                ->unique('savings'),
                            TextInput::make('amount')
                                ->required()
                                ->moneymask(),
                        ])
                        ->action(function ($data) {
                            DB::beginTransaction();
                            $member = Member::find($this->member_id);
                            $data['savings_account_id'] = $this->tableFilters['savings_account_id']['value'];
                            DepositToSavingsAccount::run($member, SavingsData::from($data));
                            DB::commit();
                        })
                        ->createAnother(false),
                    CreateAction::make('Withdraw')
                        ->label('Withdraw')
                        ->modalHeading('Withdraw Savings')
                        ->color(Color::Red)
                        ->form([
                            Select::make('payment_type_id')
                                ->paymenttype()
                                ->required(),
                            TextInput::make('amount')
                                ->required()
                                ->moneymask(),
                        ])
                        ->action(function ($data) {
                            $member = Member::find($this->member_id);
                            $data['reference_number'] = 'SW-';
                            $data['savings_account_id'] = $this->tableFilters['savings_account_id']['value'];
                            WithdrawFromSavingsAccount::run($member, SavingsData::from($data));
                        })
                        ->createAnother(false),
                    CreateAction::make('to_imprests')
                        ->label('Transfer to Imprests')
                        ->modalHeading('Transfer to Imprests')
                        ->color(Color::Amber)
                        ->form([
                            TextInput::make('amount')
                                ->required()
                                ->moneymask(),
                        ])
                        ->action(function ($data) {
                            DB::beginTransaction();
                            $member = Member::find($this->member_id);
                            $data['savings_account_id'] = $this->tableFilters['savings_account_id']['value'];
                            $data['payment_type_id'] = 1;
                            $data['reference_number'] = SavingsProvider::FROM_TRANSFER_CODE;
                            $st = WithdrawFromSavingsAccount::run($member, SavingsData::from($data));
                            unset($data['savings_account_id']);
                            $data['reference_number'] = $st->reference_number;
                            DepositToImprestAccount::run($member, ImprestData::from($data));
                            DB::commit();
                        })
                        ->createAnother(false),
                ])
                    ->button()
                    ->label('Transaction')
                    ->icon('heroicon-o-banknotes')
                    ->visible(fn () => filled($this->tableFilters['savings_account_id']['value'])),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.savings-subsidiary-ledger', ['member' => $this->member_id])),
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
