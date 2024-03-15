<?php

namespace App\Livewire\App;

use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\GenerateAccountNumber;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\Providers\SavingsProvider;
use DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Tables;
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
                            ->default(Member::find($this->member_id)->full_name)
                            ->required(),
                        TextInput::make('number')
                            ->default(app(GenerateAccountNumber::class)->handle(member_type_id: Member::find($this->member_id)->member_type_id))
                            ->required(),
                    ])
                    ->action(function ($data) {
                        app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                            member_id: $this->member_id,
                            name: $data['name'],
                        ));
                        Notification::make()->title('Savings account created!')->success()->send();
                    })
                    ->visible(auth()->user()->can('manage mso'))
                    ->color(Color::Emerald)
                    ->createAnother(false),
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
