<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    public function getHeading(): string|Htmlable
    {
        return "Viewing Member {$this->record->full_name}";
    }
}
