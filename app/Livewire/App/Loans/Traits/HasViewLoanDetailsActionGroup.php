<?php

namespace App\Livewire\App\Loans\Traits;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

trait HasViewLoanDetailsActionGroup
{
    private static function getStaticViewLoanDetailsActionGroup()
    {
        return ActionGroup::make([
            Action::make('loan_application')
                ->icon('heroicon-o-document')
                ->label('View Application')
                ->url(fn ($record) => route('filament.app.resources.loan-applications.view', ['record' => $record->loan_application])),
            Action::make('payments')
                ->icon('heroicon-o-currency-dollar')
                ->visible(fn ($record) => $record->posted)
                ->modalContent(fn ($record) => view('filament.app.views.loan-payments', ['loan' => $record])),
            Action::make('amortization')
                ->label('Amortization Schedule')
                ->icon('heroicon-o-calendar-days')
                ->visible(fn ($record) => $record->posted)
                ->url(fn ($record) => route('filament.app.resources.members.loan-amortization-schedule', ['loan' => $record])),
            Action::make('sl')
                ->label('Subsidiary Ledger')
                ->icon('heroicon-o-queue-list')
                ->visible(fn ($record) => $record->posted)
                ->url(fn ($record) => route('filament.app.resources.members.loan-subsidiary-ledger', ['loan' => $record])),
            Action::make('ds')
                ->label('Disclosure Sheet')
                ->icon('heroicon-o-document')
                ->visible(fn ($record) => $record->posted)
                ->url(fn ($record) => route('filament.app.resources.members.loan-disclosure-sheet', ['loan' => $record])),
        ])
            ->button()
            ->outlined()
            ->icon(false)
            ->label('View');
    }

    private function getViewLoanDetailsActionGroup()
    {
        return ActionGroup::make([
            Action::make('loan_application')
                ->icon('heroicon-o-document')
                ->label('View Application')
                ->url(fn ($record) => route('filament.app.resources.loan-applications.view', ['record' => $record->loan_application])),
            Action::make('payments')
                ->icon('heroicon-o-currency-dollar')
                ->visible(fn ($record) => $record->posted)
                ->modalContent(fn ($record) => view('filament.app.views.loan-payments', ['loan' => $record])),
            Action::make('amortization')
                ->label('Amortization Schedule')
                ->icon('heroicon-o-calendar-days')
                ->visible(fn ($record) => $record->posted)
                ->url(fn ($record) => route('filament.app.resources.members.loan-amortization-schedule', ['loan' => $record])),
            Action::make('sl')
                ->label('Subsidiary Ledger')
                ->icon('heroicon-o-queue-list')
                ->visible(fn ($record) => $record->posted)
                ->url(fn ($record) => route('filament.app.resources.members.loan-subsidiary-ledger', ['loan' => $record])),
            Action::make('ds')
                ->label('Disclosure Sheet')
                ->icon('heroicon-o-document')
                ->visible(fn ($record) => $record->posted)
                ->url(fn ($record) => route('filament.app.resources.members.loan-disclosure-sheet', ['loan' => $record])),
        ])
            ->button()
            ->outlined()
            ->icon(false)
            ->label('View');
    }

    private static function getViewLoanApplicationLoanDetailsActionGroup()
    {
        return ActionGroup::make([
            Action::make('loan_application')
                ->icon('heroicon-o-document')
                ->label('View Application')
                ->url(fn ($record) => route('filament.app.resources.loan-applications.view', ['record' => $record])),
            Action::make('payments')
                ->icon('heroicon-o-currency-dollar')
                ->modalContent(fn ($record) => view('filament.app.views.loan-payments', ['loan' => $record->loan])),
            Action::make('amortization')
                ->label('Amortization Schedule')
                ->icon('heroicon-o-calendar-days')
                ->url(fn ($record) => route('filament.app.resources.members.loan-amortization-schedule', ['loan' => $record->loan])),
            Action::make('sl')
                ->label('Subsidiary Ledger')
                ->icon('heroicon-o-queue-list')
                ->url(fn ($record) => route('filament.app.resources.members.loan-subsidiary-ledger', ['loan' => $record->loan])),
            Action::make('ds')
                ->label('Disclosure Sheet')
                ->icon('heroicon-o-document')
                ->url(fn ($record) => route('filament.app.resources.members.loan-disclosure-sheet', ['loan' => $record->loan])),
        ])
            ->button()
            ->outlined()
            ->icon(false)
            ->label('View');
    }
}
