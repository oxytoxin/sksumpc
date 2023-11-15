<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\App\Resources\LoanApplicationResource;

class ViewLoanApplication extends ViewRecord
{
    protected static string $resource = LoanApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back to previous page')
                ->url(back()->getTargetUrl()),
        ];
    }
}
