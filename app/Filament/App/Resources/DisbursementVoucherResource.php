<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Rules\BalancedJev;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TrialBalanceEntry;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use App\Filament\App\Resources\DisbursementVoucherResource\Pages;
use App\Filament\App\Resources\DisbursementVoucherResource\RelationManagers;
use App\Filament\App\Resources\DisbursementVoucherResource\Pages\ManageDisbursementVouchers;

class DisbursementVoucherResource extends Resource
{
    protected static ?string $model = DisbursementVoucher::class;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cdj_column')
                    ->label('Type')
                    ->options([
                        1 => 'CDJ-LOANS',
                        2 => 'CDJ-OTHERS',
                        3 => 'CDJ-MSO',
                        4 => 'CDJ-RICE',
                    ])
                    ->required(),
                TextInput::make('name')->required(),
                DatePicker::make('transaction_date')->required()->default(today())->native(false),
                TextInput::make('address')->required(),
                TextInput::make('reference_number')->required(),
                Textarea::make('description')->columnSpanFull()->required(),
                TableRepeater::make('disbursement_voucher_items')
                    ->hideLabels()
                    ->relationship()
                    ->columnSpanFull()
                    ->columnWidths(['trial_balance_entry_id' => '20rem'])
                    ->schema([
                        Select::make('trial_balance_entry_id')
                            ->options(
                                TrialBalanceEntry::whereNotNull('code')
                                    ->pluck('codename', 'id')
                            )
                            ->searchable()
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
                    ->query(fn ($query, $data) => $query->when($data['value'], fn ($q) => $q->whereRelation('disbursement_voucher_items', 'trial_balance_entry_id', $data['value'])))
            ])
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('success')
                    ->outlined()
                    ->modalHeading("Disbursement Voucher Preview")
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->modalContent(fn ($record) => view('components.app.bookkeeper.reports.disbursement-voucher-preview', ['disbursement_voucher' => $record])),
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
            'index' => Pages\ManageDisbursementVouchers::route('/'),
        ];
    }
}
