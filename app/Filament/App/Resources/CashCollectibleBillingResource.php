<?php

namespace App\Filament\App\Resources;

use App\Actions\CashCollectionBilling\PostCashCollectibleBillingPayments;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Models\CashCollectibleBilling;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\CashCollectibleBillingResource\Pages;
use App\Filament\App\Resources\CashCollectibleBillingResource\RelationManagers;
use App\Models\CashCollectible;
use App\Models\PaymentType;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class CashCollectibleBillingResource extends Resource
{
    protected static ?string $model = CashCollectibleBilling::class;

    protected static ?string $navigationGroup = 'Share Capital';
    protected static ?string $navigationLabel = 'Stakeholders';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cash_collectible_id')
                    ->options(CashCollectible::pluck('name', 'id'))
                    ->label('Cash Collectible')
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->default(null)
                    ->selectablePlaceholder(true),
                DatePicker::make('date')
                    ->disabled()
                    ->dehydrated()
                    ->default(config('app.transaction_date'))
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('billable_date'),
                TextColumn::make('date')->date('m/d/Y')->label('Date Generated'),
                TextColumn::make('reference_number'),
                TextColumn::make('or_number')
                    ->label('OR Approved'),
                IconColumn::make('posted')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => !$record->posted)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number'),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !$record->posted)
                    ->action(function (CashCollectibleBilling $record) {
                        $record->cash_collectible_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('for_or')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => !$record->posted && !$record->for_or && !$record->or_number && $livewire->user_is_cashier)
                    ->label('For OR')
                    ->requiresConfirmation()
                    ->action(function (CashCollectibleBilling $record) {
                        if ($record->cash_collectible_billing_payments()->doesntExist()) {
                            Notification::make()->title('No content, Subject for Review')->danger()->send();
                            return;
                        }
                        $record->update([
                            'for_or' => true,
                        ]);
                        Notification::make()->title('Cash collectible billing for OR by Cashier!')->success()->send();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => !$record->posted && !$record->for_or && $record->or_number && $livewire->user_is_cbu_officer)
                    ->requiresConfirmation()
                    ->action(function (CashCollectibleBilling $record) {
                        app(PostCashCollectibleBillingPayments::class)->handle(cashCollectibleBilling: $record);
                        Notification::make()->title('Payments posted!')->success()->send();
                    }),
                Action::make('billing_receivables')
                    ->url(fn ($record) => route('filament.app.resources.cash-collectible-billings.billing-payments', ['cash_collectible_billing' => $record]))
                    ->button()
                    ->outlined(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCashCollectibleBillings::route('/'),
            'billing-payments' => Pages\CashCollectibleBillingPayments::route('/{cash_collectible_billing}/receivables'),
        ];
    }
}
