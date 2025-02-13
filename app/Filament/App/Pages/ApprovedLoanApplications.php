<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Resources\LoanResource\Actions\ViewLoanDetailsActionGroup;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\LoanApplication;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ApprovedLoanApplications extends Page implements HasTable
{
    use HasViewLoanDetailsActionGroup, InteractsWithTable;

    protected static string $view = 'filament.app.pages.approved-loan-applications';

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
    }

    public function mount(): void
    {
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanApplication::with('loan')->whereIn('status', [LoanApplication::STATUS_APPROVED, LoanApplication::STATUS_POSTED]))
            ->columns([
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('priority_number')->searchable(),
                TextColumn::make('transaction_date')->date('m/d/Y')->label('Date Applied'),
                TextColumn::make('loan_type.name'),
                TextColumn::make('desired_amount')->money('PHP'),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($record) => $record->loan ? ($record->loan->posted ? 'POSTED' : 'PENDING') : 'APPROVED')
                    ->color(fn ($record) => $record->loan ? ($record->loan->posted ? 'success' : 'warning') : 'success')
                    ->badge(),
            ])
            ->defaultLoanApplicationFilters(type: LoanApplication::STATUS_APPROVED)
            ->actions([
                ViewLoanDetailsActionGroup::getActions(),
            ]);
    }
}
