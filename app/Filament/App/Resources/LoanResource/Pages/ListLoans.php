<?php

namespace App\Filament\App\Resources\LoanResource\Pages;

use App\Filament\App\Resources\LoanResource;
use Filament\Resources\Pages\ListRecords;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    public function mount(): void
    {
        parent::mount();
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
