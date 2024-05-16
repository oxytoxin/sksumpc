<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\JournalEntryVoucherResource\Pages;
use App\Models\Account;
use App\Models\JournalEntryVoucher;
use App\Models\Member;
use App\Models\VoucherType;
use App\Rules\BalancedBookkeepingEntries;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class JournalEntryVoucherResource extends Resource
{
    protected static ?string $model = JournalEntryVoucher::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('voucher_type_id')
                    ->label('Voucher Type')
                    ->options(VoucherType::pluck('name', 'id')),
                TextInput::make('name')->required(),
                TextInput::make('address')->required(),
                TextInput::make('reference_number')->required(),
                TextInput::make('voucher_number')->required(),
                Textarea::make('description')->columnSpanFull()->required(),
                TableRepeater::make('journal_entry_voucher_items')
                    ->hideLabels()
                    ->rule(new BalancedBookkeepingEntries)
                    ->columnSpanFull()
                    ->columnWidths(['account_id' => '13rem', 'member_id' => '13rem'])
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        $items = collect($state);
                        $cib = Account::getCashInBankGF();
                        $net_amount = $items->firstWhere('account_id', $cib?->id);
                        if ($net_amount) {
                            $items = $items->filter(fn ($i) => $i['account_id'] != $net_amount['account_id']);
                            $net_amount['credit'] = $items->sum('debit') - $items->sum('credit');
                            $items->push($net_amount);
                        }
                        $set('journal_entry_voucher_items', $items->toArray());
                    })
                    ->schema([
                        Select::make('member_id')
                            ->options(Member::pluck('full_name', 'id'))
                            ->label('Member')
                            ->searchable()
                            ->reactive()
                            ->preload(),
                        Select::make('account_id')
                            ->options(
                                fn ($get) => Account::withCode()->whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->where('member_id', $get('member_id') ?? null)->pluck('code', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->label('Account'),
                        TextInput::make('debit')
                            ->moneymask(),
                        TextInput::make('credit')
                            ->moneymask(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('voucher_type.name'),
                TextColumn::make('journal_entry_voucher_items.account.number')
                    ->label('Account Numbers')
                    ->listWithLineBreaks(),
                TextColumn::make('name'),
                TextColumn::make('reference_number'),
                TextColumn::make('description'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->dateRange('transaction_date'),
            ])
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('success')
                    ->outlined()
                    ->modalHeading('JEV Preview')
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->modalContent(fn ($record) => view('components.app.bookkeeper.reports.journal-entry-voucher-preview', ['journal_entry_voucher' => $record])),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJournalEntryVouchers::route('/'),
        ];
    }
}
