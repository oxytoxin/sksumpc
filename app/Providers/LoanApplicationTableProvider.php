<?php

    namespace App\Providers;

    use App\Models\LoanApplication;
    use App\Models\LoanType;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;
    use Illuminate\Support\ServiceProvider;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

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

                $filters[] = DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY');

                if (!$type || $type == LoanApplication::STATUS_DISAPPROVED) {
                    $filters[] = DateRangeFilter::make('disapproval_date')
                        ->format('m/d/Y')
                        ->displayFormat('MM/DD/YYYY');
                }
                /** @phpstan-ignore-next-line */
                return static::filters($filters)->filtersLayout(FiltersLayout::AboveContent);
            });
        }
    }
