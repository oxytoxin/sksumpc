<?php

namespace App\Filament\App\Resources\LoanTypeResource\Pages;

use App\Filament\App\Resources\LoanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageLoanTypes extends ManageRecords
{
    protected static string $resource = LoanTypeResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Loan Schedule';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
