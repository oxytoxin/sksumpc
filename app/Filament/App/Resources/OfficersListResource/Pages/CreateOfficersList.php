<?php

namespace App\Filament\App\Resources\OfficersListResource\Pages;

use App\Filament\App\Resources\OfficersListResource;
use App\Models\OfficersList;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOfficersList extends CreateRecord
{
    protected static string $resource = OfficersListResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $officers_list = OfficersList::create(['year' => $data['year']]);
        $officers_list->members()->sync($data['officers']);

        return $officers_list;
    }
}
