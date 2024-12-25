<?php

namespace App\Filament\App\Resources\OrganizationResource\Pages;

use App\Actions\Memberships\CreateMemberInitialAccounts;
use App\Filament\App\Resources\OrganizationResource;
use App\Models\MemberType;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrganization extends CreateRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['member_type_id'] = MemberType::firstWhere('name', 'ORGANIZATION')?->id;
        $data['is_organization'] = true;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $organization = parent::handleRecordCreation($data);
        app(CreateMemberInitialAccounts::class)->handle($organization);

        return $organization;
    }
}
