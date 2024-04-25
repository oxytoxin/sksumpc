<?php

namespace App\Providers;

use App\Models\PaymentType;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        TextInput::macro('moneymask', function () {
            $this->prefix('P')
                ->live(true)
                ->numeric()
                ->minValue(0);

            return $this;
        });


        Select::macro('paymenttype', function () {
            $this->options(PaymentType::whereIn('id', [1, 4])->pluck('name', 'id'))
                ->default(1)
                ->label('Payment Type')
                ->selectablePlaceholder(false)
                ->live();

            return $this;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(125);
        Date::use(CarbonImmutable::class);
        Model::unguard();
    }
}
