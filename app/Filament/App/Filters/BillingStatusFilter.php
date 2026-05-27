<?php

    namespace App\Filament\App\Filters;

    use Filament\Tables\Filters\SelectFilter;

    class BillingStatusFilter extends SelectFilter
    {
        public static function make(?string $name = null): static
        {
            return parent::make($name)
                ->options([
                    'posted' => 'Posted',
                    'for_or' => 'For OR',
                    'unposted' => 'Unposted',
                    'pending' => 'Pending'
                ])
                ->query(fn($query, $state) => $query
                    ->when($state['value'] == 'posted', fn($q) => $q->where('posted', true))
                    ->when($state['value'] == 'for_or', fn($q) => $q->where('for_or', true))
                    ->when($state['value'] == 'unposted', fn($q) => $q->where('posted', false)->whereNotNull('or_number'))
                    ->when($state['value'] == 'pending', fn($q) => $q->where('posted', false)->whereNull('or_number'))
                );
        }

    }