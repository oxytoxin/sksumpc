<?php

namespace App\Filament\App\Pages;

use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Loan;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\LoanApplication;
use App\Oxytoxin\LoansProvider;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

use function Filament\Support\format_money;

class NewLoanFromApplication extends Page implements HasTable
{
    use InteractsWithTable, HasViewLoanDetailsActionGroup;

    protected static string $view = 'filament.app.pages.new-loan-from-application';

    protected static ?string $navigationLabel = 'Approved Loan Applications';

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
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
                self::getViewLoanApplicationLoanDetailsActionGroup()
            ]);
    }
}
