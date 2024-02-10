<?php

namespace App\Livewire\App;

use DB;
use Filament\Tables;
use App\Models\Member;
use App\Models\Saving;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Oxytoxin\Providers\SavingsProvider;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;

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
                    ->default(SavingsAccount::whereMemberId($this->member_id)->first()?->id)
                    ->label('Account'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make('NewAccount')
                    ->label('New Account')
                    ->modalHeading('New Savings Account')
                    ->form([
                        TextInput::make('name')
                            ->required(),
                    ])
                    ->action(function ($data) {
                        app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                            member_id: $this->member_id,
                            name: $data['name'],
                        ));
                        Notification::make()->title('Savings account created!')->success()->send();
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
                            $member = Member::find($this->member_id);
                            app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                                payment_type_id: $data['payment_type_id'],
                                reference_number: $data['reference_number'],
                                amount: $data['amount'],
                                savings_account_id: $this->tableFilters['savings_account_id']['value']
                            ), TransactionType::firstWhere('name', 'CRJ'));
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
                            $data['reference_number'] = SavingsProvider::WITHDRAWAL_TRANSFER_CODE;
                            $data['savings_account_id'] = $this->tableFilters['savings_account_id']['value'];
                            app(WithdrawFromSavingsAccount::class)->handle($member, SavingsData::from($data), TransactionType::firstWhere('name', 'CRJ'));
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
                            $st = app(WithdrawFromSavingsAccount::class)->handle($member, new SavingsData(
                                payment_type_id: 1,
                                reference_number: SavingsProvider::FROM_TRANSFER_CODE,
                                amount: $data['amount'],
                                savings_account_id: $this->tableFilters['savings_account_id']['value'],
                            ), TransactionType::firstWhere('name', 'CRJ'));
                            app(DepositToImprestAccount::class)->handle($member, new ImprestData(
                                payment_type_id: 1,
                                reference_number: $st->reference_number,
                                amount: $data['amount']
                            ), TransactionType::firstWhere('name', 'CRJ'));
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
