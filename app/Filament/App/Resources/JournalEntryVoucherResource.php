<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\JournalEntryVoucherResource\Pages;
use App\Filament\App\Resources\JournalEntryVoucherResource\RelationManagers;
use App\Models\JournalEntryVoucher;
use App\Models\TrialBalanceEntry;
use App\Rules\BalancedJev;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                    ->rule(new BalancedJev)
                    ->columnSpanFull()
                    ->columnWidths(['trial_balance_entry_id' => '20rem'])
                    ->schema([
                        Select::make('trial_balance_entry_id')
                            ->options(
                                TrialBalanceEntry::whereNotNull('code')
                                    ->pluck('codename', 'id')
                            )
                            ->required()
                            ->label('Account'),
                        TextInput::make('debit')
                            ->moneymask(),
                        TextInput::make('credit')
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
                TextColumn::make('description'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->dateRange('transaction_date'),
                SelectFilter::make('trial_balance_entry_id')
                    ->label('Account')
                    ->options(TrialBalanceEntry::whereNotNull('code')->pluck('codename', 'id'))
                    ->query(fn ($query, $data) => $query->when($data['value'], fn ($q) => $q->whereRelation('journal_entry_voucher_items', 'trial_balance_entry_id', $data['value'])))
            ])
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('success')
                    ->outlined()
                    ->modalHeading("JEV Preview")
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->modalContent(fn ($record) => view('components.app.bookkeeper.reports.journal-entry-voucher-preview', ['journal_entry_voucher' => $record])),
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
