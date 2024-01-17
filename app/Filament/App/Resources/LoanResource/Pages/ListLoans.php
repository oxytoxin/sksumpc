<?php

namespace App\Filament\App\Resources\LoanResource\Pages;

use App\Filament\App\Resources\LoanResource;
use Filament\Resources\Pages\ListRecords;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    protected ?string $heading = 'Loans Posting';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
