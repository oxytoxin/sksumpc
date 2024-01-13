<?php

namespace App\Livewire\App;

use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Models\Imprest;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\ImprestsProvider;
use DB;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ImprestsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $member_id;

    #[On('refresh')]
    public function loanCreated()
    {
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Imprest::whereMemberId($this->member_id))
            ->recordClasses(fn ($record) => $record->amount > 0 ? 'bg-green-200' : 'bg-red-200')
            ->columns([
                TextColumn::make('transaction_date')->date('m/d/Y'),
                TextColumn::make('reference_number'),
                TextColumn::make('withdrawal')->label('Withdrawal')->money('PHP'),
                TextColumn::make('deposit')->label('Deposit/Interest')->money('PHP'),
                TextColumn::make('balance')->money('PHP'),
                TextColumn::make('days_till_next_transaction'),
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
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('imprests'),
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $member = Member::find($this->member_id);
                        DepositToImprestAccount::run($member, ImprestData::from($data));
                        DB::commit();
                    })
                    ->createAnother(false),
                CreateAction::make('Withdraw')
                    ->label('Withdraw')
                    ->modalHeading('Withdraw Imprest')
                    ->color(Color::Red)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('amount')
                            ->required()
                            ->moneymask(),
                        Hidden::make('reference_number')->default('IW-'),
                    ])
                    ->action(function ($data) {
                        $member = Member::find($this->member_id);
                        WithdrawFromImprestAccount::run($member, ImprestData::from($data));
                    })
                    ->createAnother(false),
                CreateAction::make('to_savings')
                    ->label('Transfer to Savings')
                    ->modalHeading('Transfer to Savings')
                    ->color(Color::Amber)
                    ->form([
                        Select::make('savings_account_id')
                            ->options(SavingsAccount::whereMemberId($this->member_id)->pluck('number', 'id'))
                            ->required()
                            ->label('Account'),
                        TextInput::make('amount')
                            ->required()
                            ->moneymask(),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $member = Member::find($this->member_id);
                        $im = WithdrawFromImprestAccount::run($member, new ImprestData(
                            payment_type_id: 1,
                            reference_number: ImprestsProvider::FROM_TRANSFER_CODE,
                            amount: $data['amount']
                        ));
                        DepositToSavingsAccount::run($member, new SavingsData(
                            payment_type_id: 1,
                            reference_number: $im->reference_number,
                            amount: $data['amount'],
                            savings_account_id: $data['savings_account_id']
                        ));
                        DB::commit();
                    })
                    ->createAnother(false),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.imprest-subsidiary-ledger', ['member' => $this->member_id])),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.imprests-table');
    }
}
