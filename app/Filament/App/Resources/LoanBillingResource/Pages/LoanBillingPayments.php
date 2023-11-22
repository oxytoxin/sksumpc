<?php

namespace App\Filament\App\Resources\LoanBillingResource\Pages;

use App\Filament\App\Resources\LoanBillingResource;
use App\Models\LoanBilling;
use App\Models\LoanBillingPayment;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class LoanBillingPayments extends ListRecords
{
    protected static string $resource = LoanBillingResource::class;

    public LoanBilling $loan_billing;

    public function getHeading(): string|Htmlable
    {
        return 'Loan Billing Receivables';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanBillingPayment::where('loan_billing_id', $this->loan_billing->id))
            ->columns([
                TextColumn::make('loan_amortization.loan.member.full_name'),
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
                    ->visible(fn ($record) => !$record->posted)
            ]);
    }
}
