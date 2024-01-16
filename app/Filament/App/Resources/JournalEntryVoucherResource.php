<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\JournalEntryVoucherResource\Pages;
use App\Filament\App\Resources\JournalEntryVoucherResource\RelationManagers;
use App\Models\JournalEntryVoucher;
use App\Models\TrialBalanceEntry;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JournalEntryVoucherResource extends Resource
{
    protected static ?string $model = JournalEntryVoucher::class;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                DatePicker::make('transaction_date')->required()->default(today())->native(false),
                TextInput::make('address')->required(),
                TextInput::make('reference_number')->required(),
                Textarea::make('description')->columnSpanFull()->required(),
                TableRepeater::make('journal_entry_voucher_items')
                    ->hideLabels()
                    ->relationship()
                    ->columnSpanFull()
                    ->columnWidths(['trial_balance_entry_id' => '20rem'])
                    ->schema([
                        Select::make('trial_balance_entry_id')
                            ->searchable(['code', 'name'])
                            ->options(
                                TrialBalanceEntry::doesntHave('descendants')
                                    ->selectRaw("concat(code, ' - ', upper(name)) as codename")
                                    ->pluck('codename')
                            )
                            ->required()
                            ->label('Account'),
                        TextInput::make('debit')
                            ->required(fn ($get) => !$get('credit'))
                            ->moneymask(),
                        TextInput::make('credit')
                            ->required(fn ($get) => !$get('debit'))
                            ->moneymask(),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('reference_number'),
                TextColumn::make('transaction_date')->date('F d, Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJournalEntryVouchers::route('/'),
        ];
    }
}
