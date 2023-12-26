<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Resources\LoanApplicationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewLoanApplication extends ViewRecord
{
    protected static string $resource = LoanApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back to previous page')
                ->extraAttributes(['wire:ignore' => true])
                ->url(back()->getTargetUrl()),
        ];
    }
}
