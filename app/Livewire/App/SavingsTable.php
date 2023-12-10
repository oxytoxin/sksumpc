<?php

namespace App\Livewire\App;

use DB;
use Carbon\Carbon;
use Filament\Tables;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Oxytoxin\ImprestData;
use App\Oxytoxin\SavingsData;
use App\Oxytoxin\SavingsProvider;
use App\Oxytoxin\ImprestsProvider;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use function Filament\Support\format_money;
use Filament\Tables\Actions\BulkActionGroup;

use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;

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
                TextColumn::make('number_of_days'),
                TextColumn::make('interest')->money('PHP'),
            ])
            ->filters([
                SelectFilter::make('savings_account_id')
                    ->options(SavingsAccount::whereMemberId($this->member_id)->pluck('name', 'id'))
                    ->label('Account')
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([])
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
                            ...$data
                        ]);
                    })
                    ->color(Color::Emerald)
                    ->createAnother(false),
                ActionGroup::make([
                    CreateAction::make('Deposit')
                        ->label('Deposit')
                        ->modalHeading('Deposit Savings')
                        ->form([
                            DatePicker::make('transaction_date')->required()->default(today()),
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
                            $member =  Member::find($this->member_id);
                            SavingsProvider::createSavings($member, (new SavingsData(...$data, savings_account_id: $this->tableFilters['savings_account_id']['value'])));
                            DB::commit();
                        })
                        ->createAnother(false),
                    CreateAction::make('Withdraw')
                        ->label('Withdraw')
                        ->modalHeading('Withdraw Savings')
                        ->color(Color::Red)
                        ->form([
                            DatePicker::make('transaction_date')->required()->default(today()),
                            Select::make('payment_type_id')
                                ->paymenttype()
                                ->required(),
                            TextInput::make('amount')
                                ->required()
                                ->moneymask(),
                        ])
                        ->action(function ($data) {
                            $data['amount'] = $data['amount'] * -1;
                            DB::beginTransaction();
                            $member =  Member::find($this->member_id);
                            $data['reference_number'] = '';
                            SavingsProvider::createSavings($member, (new SavingsData(...$data, savings_account_id: $this->tableFilters['savings_account_id']['value'])));
                            DB::commit();
                        })
                        ->createAnother(false),
                    CreateAction::make('to_imprests')
                        ->label('Transfer to Imprests')
                        ->modalHeading('Transfer to Imprests')
                        ->color(Color::Amber)
                        ->form([
                            DatePicker::make('transaction_date')->required()->default(today()),
                            TextInput::make('amount')
                                ->required()
                                ->moneymask(),
                        ])
                        ->action(function ($data) {
                            DB::beginTransaction();
                            $member =  Member::find($this->member_id);
                            $data['type'] = 'OR';
                            $data['amount'] = $data['amount'] * -1;
                            $data['reference_number'] = SavingsProvider::FROM_TRANSFER_CODE;
                            $st = SavingsProvider::createSavings($member, (new SavingsData(...$data)));
                            $data['amount'] = $data['amount'] * -1;
                            $data['reference_number'] = $st->reference_number;
                            ImprestsProvider::createImprest($member, (new ImprestData(...$data)));
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
