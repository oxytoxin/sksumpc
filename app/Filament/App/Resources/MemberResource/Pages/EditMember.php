<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Actions\Memberships\UpdateMemberCreditAndBackground;
use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\MemberResource;
use App\Models\OfficersList;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMember extends EditRecord
{
    use RequiresBookkeeperTransactionDate;

    protected static string $resource = MemberResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['credit_and_background'] = MemberResource::getCreditAndBackgroundFormData($this->getRecord());

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $creditAndBackgroundData = $data['credit_and_background'] ?? [];
        unset($data['credit_and_background']);

        if ($data['officers_list_id'] && $data['position_id']) {
            $officers_list = OfficersList::find($data['officers_list_id']);
            $officers_list->members()->sync([$record->id => ['position_id' => $data['position_id']]]);
        }
        unset($data['officers_list_id'], $data['position_id']);
        $record->update($data);

        if ($record->credit_and_background()->exists() || $this->hasCreditAndBackgroundData($creditAndBackgroundData)) {
            app(UpdateMemberCreditAndBackground::class)->handle($record, $creditAndBackgroundData);
        }

        return $record;
    }

    private function hasCreditAndBackgroundData(array $data): bool
    {
        foreach ($data as $value) {
            if (is_array($value)) {
                if ($this->hasCreditAndBackgroundData($value)) {
                    return true;
                }

                continue;
            }

            if (filled($value)) {
                return true;
            }
        }

        return false;
    }
}
