<?php

namespace App\Livewire\App;

use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Models\LoveGift;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class LoveGiftsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public $member_id;

    #[On('refresh')]
    public function loanCreated()
    {
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoveGift::whereMemberId($this->member_id))
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
                        DepositToLoveGiftsAccount::run($member, new LoveGiftData(
                            payment_type_id: $data['payment_type_id'],
                            reference_number: $data['reference_number'],
                            amount: $data['amount'],
                        ));
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
                        WithdrawFromLoveGiftsAccount::run($member, new LoveGiftData(
                            payment_type_id: $data['payment_type_id'],
                            reference_number: $data['reference_number'],
                            amount: $data['amount']
                        ));
                    })
                    ->createAnother(false),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.love-gifts-subsidiary-ledger', ['member' => $this->member_id])),
            ])
            ->actions([])
            ->bulkActions([
                BulkActionGroup::make([]),
            ]);
    }

    public function render()
    {
        return view('livewire.app.love-gifts-table');
    }
}
