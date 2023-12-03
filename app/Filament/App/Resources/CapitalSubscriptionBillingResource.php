<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CapitalSubscriptionBilling;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;
use App\Filament\App\Resources\CapitalSubscriptionBillingResource\RelationManagers;
use App\Models\CapitalSubscriptionBillingPayment;
use DB;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class CapitalSubscriptionBillingResource extends Resource
{
    protected static ?string $model = CapitalSubscriptionBilling::class;

    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->default(null)
                    ->selectablePlaceholder(true),
                TextInput::make('reference_number')
                    ->unique('loan_billings'),
                DatePicker::make('date')
                    ->date()
                    ->required()
                    ->native(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('billable_date'),
                TextColumn::make('created_at')->date('m/d/Y')->label('Date Generated'),
                TextColumn::make('reference_number'),
                IconColumn::make('posted')
                    ->boolean()
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
                        TextInput::make('reference_number')
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !$record->posted)
                    ->action(function (CapitalSubscriptionBilling $record) {
                        $record->capital_subscription_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record) => !$record->posted)
                    ->requiresConfirmation()
                    ->action(function (CapitalSubscriptionBilling $record) {
                        if (!$record->reference_number || !$record->payment_type_id) {
                            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
                        }
                        DB::beginTransaction();
                        $record->capital_subscription_billing_payments()->each(function (CapitalSubscriptionBillingPayment $cp) use ($record) {
                            $cbu = $cp->capital_subscription_amortization->capital_subscription;
                            $amortization = $cp->capital_subscription_amortization;
                            $amortization->update([
                                'amount_paid' => $cp->amount_paid,
                            ]);
                            $cbu->payments()->createQuietly([
                                'cashier_id' => auth()->id(),
                                'running_balance' => $cbu->outstanding_balance - $cp->amount_paid,
                                'payment_type_id' => $record->payment_type_id,
                                'reference_number' => $record->reference_number,
                                'amount' => $amortization->amount_paid,
                                'transaction_date' => today(),
                            ]);
                            $cp->update([
                                'posted' => true
                            ]);
                        });
                        $record->update([
                            'posted' => true
                        ]);
                        DB::commit();
                        Notification::make()->title('Payments posted!')->success()->send();
                    }),
                Action::make('billing_receivables')
                    ->url(fn ($record) => route('filament.app.resources.capital-subscription-billings.billing-payments', ['capital_subscription_billing' => $record]))
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
            'index' => Pages\ManageCapitalSubscriptionBillings::route('/'),
            'billing-payments' => Pages\CapitalSubscriptionBillingPayments::route('/{capital_subscription_billing}/receivables'),
        ];
    }
}
