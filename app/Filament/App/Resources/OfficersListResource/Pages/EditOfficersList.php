<?php

namespace App\Filament\App\Resources\OfficersListResource\Pages;

use App\Filament\App\Resources\OfficersListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditOfficersList extends EditRecord
{
    protected static string $resource = OfficersListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update(['year' => $data['year']]);
        $record->members()->sync($data['officers']);

        return $record;
    }
}
