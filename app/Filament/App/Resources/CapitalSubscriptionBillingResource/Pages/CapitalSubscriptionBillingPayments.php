<?php

namespace App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionBillingResource;
use App\Models\CapitalSubscriptionBilling;
use App\Models\CapitalSubscriptionBillingPayment;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class CapitalSubscriptionBillingPayments extends ListRecords
{
    protected static string $resource = CapitalSubscriptionBillingResource::class;

    protected static string $view = 'filament.app.resources.capital-subscription-billing-resource.pages.capital-subscription-billing-payments';

    public CapitalSubscriptionBilling $capital_subscription_billing;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Receivables';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back to previous page')
                ->extraAttributes(['wire:ignore' => true])
                ->url(back()->getTargetUrl()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CapitalSubscriptionBillingPayment::query()
                    ->where('capital_subscription_billing_id', $this->capital_subscription_billing->id)
                    ->join('members', 'capital_subscription_billing_payments.member_id', 'members.id')
                    ->selectRaw('capital_subscription_billing_payments.*, members.alt_full_name as member_name')
                    ->orderBy('member_name')
            )
            ->columns([
                TextColumn::make('member_name')->label('Member'),
                TextColumn::make('amount_due')->money('PHP')->summarize(Sum::make()->money('PHP')->label('')),
                TextColumn::make('amount_paid')->money('PHP')->summarize(Sum::make()->money('PHP')->label('')),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('amount_paid')
                            ->default(fn ($record) => $record->amount_paid)
                            ->moneymask()
                    ])
                    ->visible(fn ($record) => !$record->posted),
                DeleteAction::make()
                    ->visible(fn ($record) => !$record->posted),
            ]);
    }
}
