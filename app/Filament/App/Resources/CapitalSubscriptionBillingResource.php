<?php

namespace App\Filament\App\Resources;

use App\Actions\CapitalSubscriptionBilling\PostCapitalSubscriptionBillingPayments;
use App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;
use App\Models\CapitalSubscriptionBilling;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CapitalSubscriptionBillingResource extends Resource
{
    protected static ?string $model = CapitalSubscriptionBilling::class;

    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu') || auth()->user()->can('manage payments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->default(null)
                    ->selectablePlaceholder(true),
                DatePicker::make('date')
                    ->date()
                    ->afterOrEqual(today())
                    ->validationMessages([
                        'after_or_equal' => 'The date must be after or equal to today.',
                    ])
                    ->required()
                    ->native(false),
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
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => ! $record->posted)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number'),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => ! $record->posted)
                    ->action(function (CapitalSubscriptionBilling $record) {
                        $record->capital_subscription_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('approve_or')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => ! $record->posted && ! $record->or_approved && $livewire->user_is_cashier)
                    ->label('Approve OR')
                    ->requiresConfirmation()
                    ->form([
                        Placeholder::make('reference_number')->content(fn ($record) => $record->reference_number)->inlineLabel(),
                        Placeholder::make('amount')->content(fn ($record) => $record->capital_subscription_billing_payments()->sum('amount_paid'))->inlineLabel(),
                        Placeholder::make('payment_type')->content(fn ($record) => $record->payment_type->name)->inlineLabel(),
                        TextInput::make('name')->required(),
                        TextInput::make('or_number')->required()->label('OR #'),
                    ])
                    ->action(function (CapitalSubscriptionBilling $record, $data) {
                        $record->update([
                            'name' => $data['name'],
                            'or_number' => $data['or_number'],
                            'or_approved' => true,
                        ]);
                        Notification::make()->title('OR Approved for loan billing!')->success()->send();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => ! $record->posted && $record->or_approved && $livewire->user_is_cbu_officer)
                    ->requiresConfirmation()
                    ->action(function (CapitalSubscriptionBilling $record) {
                        app(PostCapitalSubscriptionBillingPayments::class)->handle(cbuBilling: $record);
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
