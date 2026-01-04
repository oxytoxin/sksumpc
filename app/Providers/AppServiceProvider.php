<?php

    namespace App\Providers;

    use App\Models\PaymentType;
    use Carbon\CarbonImmutable;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Schemas\Components\Fieldset;
    use Filament\Schemas\Components\Grid;
    use Filament\Schemas\Components\Section;
    use Filament\Tables\Table;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Date;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\ServiceProvider;
    use LogViewer;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            TextInput::macro('moneymask', function () {
                /** phpstan-ignore-next-line */
                $this->prefix('P')
                    ->live(true)
                    ->numeric()
                    ->minValue(0);

                return $this;
            });

            Select::macro('paymenttype', function () {
                /** phpstan-ignore-next-line */
                $this->options(PaymentType::whereIn('id', [1, 3, 4, 5])->pluck('name', 'id'))
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

            LogViewer::auth(function ($request) {
                return config('app.debug');
            });


            //FILAMENT BACKWARDS COMPAT
            Fieldset::configureUsing(fn(Fieldset $fieldset) => $fieldset
                ->columnSpanFull());

            Grid::configureUsing(fn(Grid $grid) => $grid
                ->columnSpanFull());

            Section::configureUsing(fn(Section $section) => $section
                ->columnSpanFull());

            DatePicker::configureUsing(fn(DatePicker $datepicker) => $datepicker->native(false));
            Table::configureUsing(fn(Table $table) => $table->deferFilters(false));
        }
    }
