<?php

namespace App\Filament\App\Resources\JournalEntryVoucherResource\Pages;

use App\Filament\App\Resources\JournalEntryVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalEntryVouchers extends ManageRecords
{
    protected static string $resource = JournalEntryVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
