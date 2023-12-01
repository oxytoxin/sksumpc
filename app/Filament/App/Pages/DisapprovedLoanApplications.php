<?php

namespace App\Filament\App\Pages;

use App\Models\LoanType;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\LoanApplication;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class DisapprovedLoanApplications extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.app.pages.disapproved-loan-applications';

    protected static ?string $navigationLabel = 'Disapproved Loan Applications';

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanApplication::whereStatus(LoanApplication::STATUS_DISAPPROVED))
            ->columns([
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('transaction_date')->date('m/d/Y')->label('Date Applied'),
                TextColumn::make('loan_type.name'),
                TextColumn::make('desired_amount')->money('PHP'),
                TextColumn::make('disapproval_date')->date('m/d/Y')->label('Date Disapproved'),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        LoanApplication::STATUS_DISAPPROVED => 'Disapproved',
                    })
                    ->colors([
                        'danger' => LoanApplication::STATUS_DISAPPROVED,
                    ])
                    ->badge(),
                TextColumn::make('disapproval_reason.name'),
                TextColumn::make('remarks'),
            ])
            ->defaultLoanApplicationFilters(type: LoanApplication::STATUS_DISAPPROVED);
    }
}
