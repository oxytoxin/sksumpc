<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Member;
use DB;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Member $record) {
                    DB::beginTransaction();
                    $record->membership_acceptance()->delete();
                    $record->membership_termination()->delete();
                    if ($record->capital_subscriptions()->exists()) {
                        DB::rollBack();
                        Notification::make()->title('Capital subscription exists.')->danger()->send();
                        $this->closeActionModal();
                        $this->halt();
                    } else {
                        DB::commit();
                    }
                }),
        ];
    }
}
