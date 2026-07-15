<?php

namespace App\Filament\App\Resources\LoanResource\Actions;

use App\Models\Loan;
use App\Models\LoanApplication;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

class ViewLoanDetailsActionGroup
{
    public static function getActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('loan_application')
                ->icon('heroicon-o-document')
                ->label('View Application')
                ->url(fn ($record) => route('filament.app.resources.loan-applications.view', ['record' => $record instanceof LoanApplication ? $record : $record->loan_application])),
            Action::make('coborrowers_undertaking')
                ->icon('heroicon-o-document')
                ->label("View Coborrower's Undertaking")
                ->url(fn ($record) => route('filament.app.resources.loan-applications.coborrowers_undertaking', ['loan_application' => $record instanceof LoanApplication ? $record : $record->loan_application])),
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
            Action::make('dv_view')
                ->visible(fn ($record) => $record instanceof Loan ? $record->disbursement_voucher : $record->loan?->disbursement_voucher)
                ->label('Disbursement Voucher')
                ->icon('heroicon-o-document')
                ->modalHeading('Disbursement Voucher Preview')
                ->modalCancelAction(false)
                ->modalSubmitAction(false)
                ->modalContent(fn ($record) => view('components.app.bookkeeper.reports.disbursement-voucher-preview', ['disbursement_voucher' => $record instanceof Loan ? $record->disbursement_voucher : $record->loan?->disbursement_voucher])),
            Action::make('jev_view')
                ->visible(fn ($record): bool => (bool) ($record instanceof Loan ? $record->journal_entry_voucher : $record->loan?->journal_entry_voucher))
                ->label('Journal Entry Voucher')
                ->icon('heroicon-o-document')
                ->modalHeading('Journal Entry Voucher Preview')
                ->modalCancelAction(false)
                ->modalSubmitAction(false)
                ->modalContent(fn ($record) => view('components.app.bookkeeper.reports.journal-entry-voucher-preview', ['journal_entry_voucher' => $record instanceof Loan ? $record->journal_entry_voucher : $record->loan?->journal_entry_voucher])),
        ])
            ->button()
            ->outlined()
            ->icon(false)
            ->label('View');
    }
}
