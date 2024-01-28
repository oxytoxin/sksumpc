<?php

namespace App\Filament\App\Resources\DisbursementVoucherResource\Pages;

use App\Filament\App\Resources\DisbursementVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDisbursementVouchers extends ManageRecords
{
    protected static string $resource = DisbursementVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
