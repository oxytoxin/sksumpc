<?php

namespace App\Providers;

use App\Models\DisapprovalReason;
use App\Models\LoanType;
use Filament\Tables\Table;
use App\Models\LoanApplication;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class LoanApplicationTableProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Table::macro('defaultLoanApplicationFilters', function ($type = 0) {
            $filters = [
                SelectFilter::make('loan_type_id')
                    ->label('Loan Type')
                    ->options(LoanType::orderBy('name')->pluck('name', 'id')),
            ];
            if ($type) {
                if ($type == LoanApplication::STATUS_DISAPPROVED) {
                    $filters[] = SelectFilter::make('disapproval_reason_id')
                        ->label('Disapproval Reason')
                        ->relationship('disapproval_reason', 'name');
                }
                if ($type == LoanApplication::STATUS_APPROVED) {
                    $filters[] = SelectFilter::make('status')
                        ->options([
                            LoanApplication::STATUS_APPROVED => 'Approved',
                            LoanApplication::STATUS_POSTED => 'Posted',
                        ]);
                }
            } else {
                $filters[] = SelectFilter::make('status')
                    ->options([
                        LoanApplication::STATUS_PROCESSING => 'For Approval',
                        LoanApplication::STATUS_APPROVED => 'Approved',
                        LoanApplication::STATUS_DISAPPROVED => 'Disapproved',
                        LoanApplication::STATUS_POSTED => 'Posted',
                    ]);
            }
            $filters[] = Filter::make('date_applied')
                ->form([
                    DatePicker::make('applied_from')->native(false),
                    DatePicker::make('applied_until')->native(false),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['applied_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                        )
                        ->when(
                            $data['applied_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                        );
                });

            if ($type) {
                $filters[] = Filter::make('date_disapproved')
                    ->form([
                        DatePicker::make('disapproved_from')->native(false),
                        DatePicker::make('disapproved_until')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['disapproved_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('disapproval_date', '>=', $date),
                            )
                            ->when(
                                $data['disapproved_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('disapproval_date', '<=', $date),
                            );
                    });
            }
            return static::filters($filters)
                ->filtersLayout(FiltersLayout::AboveContent);
        });
    }
}
