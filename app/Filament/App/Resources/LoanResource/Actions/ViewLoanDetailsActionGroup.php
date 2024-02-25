<?php

namespace App\Filament\App\Resources\LoanResource\Actions;

use App\Models\Loan;
use App\Models\LoanApplication;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

class ViewLoanDetailsActionGroup
{
    public static function getActions()
    {
        return ActionGroup::make([
            Action::make('loan_application')
                ->icon('heroicon-o-document')
                ->label('View Application')
                ->url(fn ($record) => route('filament.app.resources.loan-applications.view', ['record' => $record instanceof LoanApplication ? $record : $record->loan_application])),
            Action::make('payments')
                ->icon('heroicon-o-currency-dollar')
                ->visible(fn ($record) => $record instanceof Loan ? $record : $record->loan)
                ->modalContent(fn ($record) => view('filament.app.views.loan-payments', ['loan' => $record instanceof Loan ? $record : $record->loan])),
            Action::make('amortization')
                ->label('Amortization Schedule')
                ->icon('heroicon-o-calendar-days')
                ->visible(fn ($record) => $record instanceof Loan ? $record : $record->loan)
                ->url(fn ($record) => route('filament.app.resources.members.loan-amortization-schedule', ['loan' => $record instanceof Loan ? $record : $record->loan])),
            Action::make('sl')
                ->label('Subsidiary Ledger')
                ->icon('heroicon-o-queue-list')
                ->visible(fn ($record) => $record instanceof Loan ? $record : $record->loan)
                ->url(fn ($record) => route('filament.app.resources.members.loan-subsidiary-ledger', ['loan' => $record instanceof Loan ? $record : $record->loan])),
            Action::make('ds')
                ->label('Disclosure Sheet')
                ->icon('heroicon-o-document')
                ->visible(fn ($record) => $record instanceof Loan ? $record : $record->loan)
                ->url(fn ($record) => route('filament.app.resources.members.loan-disclosure-sheet', ['loan' => $record instanceof Loan ? $record : $record->loan])),
        ])
            ->button()
            ->outlined()
            ->icon(false)
            ->label('View');
    }
}
