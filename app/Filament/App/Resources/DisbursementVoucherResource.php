<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DisbursementVoucherResource\Pages;
use App\Models\Account;
use App\Models\DisbursementVoucher;
use App\Models\Member;
use App\Rules\BalancedBookkeepingEntries;
use Auth;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class DisbursementVoucherResource extends Resource
{
    protected static ?string $model = DisbursementVoucher::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage bookkeeping');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('address')->required(),
                TextInput::make('reference_number')->required(),
                TextInput::make('voucher_number')->required(),
                Textarea::make('description')->columnSpanFull()->required(),
                TableRepeater::make('disbursement_voucher_items')
                    ->hideLabels()
                    ->columnSpanFull()
                    ->columnWidths(['account_id' => '13rem', 'member_id' => '13rem'])
                    ->rule(new BalancedBookkeepingEntries)
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
                        $set('disbursement_voucher_items', $items->toArray());
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
                TextColumn::make('disbursement_voucher_items.account.number')
                    ->label('Account Numbers')
                    ->listWithLineBreaks(),
                TextColumn::make('name'),
                TextColumn::make('reference_number'),
                TextColumn::make('voucher_number'),
                TextColumn::make('description'),
            ])
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('success')
                    ->outlined()
                    ->modalHeading('Disbursement Voucher Preview')
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->modalContent(fn ($record) => view('components.app.bookkeeper.reports.disbursement-voucher-preview', ['disbursement_voucher' => $record])),

            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDisbursementVouchers::route('/'),
        ];
    }
}
