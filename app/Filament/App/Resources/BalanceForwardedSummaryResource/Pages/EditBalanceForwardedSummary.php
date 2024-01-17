<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;

use App\Filament\App\Resources\BalanceForwardedSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalanceForwardedSummary extends EditRecord
{
    protected static string $resource = BalanceForwardedSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function fillForm(): void
    {
        $record = $this->getRecord();
        $data = $record->attributesToArray();

        $data['month'] = $record->generated_date?->month;
        $data['year'] = $record->generated_date?->year;

        /** @internal Read the DocBlock above the following method. */
        $this->fillFormWithDataAndCallHooks($data);
    }
}
