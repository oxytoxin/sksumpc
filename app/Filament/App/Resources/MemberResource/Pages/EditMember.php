<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\MemberResource;
use App\Models\OfficersList;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMember extends EditRecord
{
    use RequiresBookkeeperTransactionDate;

    protected static string $resource = MemberResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['officers_list_id'] && $data['position_id']) {
            $officers_list = OfficersList::find($data['officers_list_id']);
            $officers_list->members()->sync([$record->id => ['position_id' => $data['position_id']]]);
        }
        unset($data['officers_list_id'], $data['position_id']);
        $record->update($data);
        return $record;
    }


}
