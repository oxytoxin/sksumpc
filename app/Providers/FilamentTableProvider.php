<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class FilamentTableProvider extends ServiceProvider
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
        Filter::macro('dateRange', function (string $column) {
            return static::make($column)
                ->form([
                    DatePicker::make('from')->native(false),
                    DatePicker::make('to')->native(false),
                ])
                ->columns(2)
                ->columnSpan(2)
                ->query(
                    fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($query, $date) => $query->whereDate($column, '>=', $date))
                        ->when($data['to'], fn ($query, $date) => $query->whereDate($column, '<=', $date))
                );
        });
        Collection::macro('ksort', function () {
            ksort($this->items);
            return $this;
        });
    }
}
