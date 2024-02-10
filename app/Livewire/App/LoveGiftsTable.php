<?php

namespace App\Livewire\App;

use App\Models\Member;
use Livewire\Component;
use App\Models\LoveGift;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use App\Models\TransactionType;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use App\Oxytoxin\Providers\LoveGiftProvider;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;

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
                    ->modalHeading('Deposit Love Gift')
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('love_gifts'),
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $member = Member::find($this->member_id);
                        app(DepositToLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                            payment_type_id: $data['payment_type_id'],
                            reference_number: $data['reference_number'],
                            amount: $data['amount'],
                        ), TransactionType::firstWhere('name', 'CRJ'));
                        DB::commit();
                    })
                    ->createAnother(false),
                CreateAction::make('Withdraw')
                    ->label('Withdraw')
                    ->modalHeading('Withdraw Love Gift')
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
                        app(WithdrawFromLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                            payment_type_id: $data['payment_type_id'],
                            reference_number: LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE,
                            amount: $data['amount']
                        ), TransactionType::firstWhere('name', 'CRJ'));
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
