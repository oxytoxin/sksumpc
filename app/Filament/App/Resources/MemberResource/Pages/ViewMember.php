<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    public function getHeading(): string|Htmlable
    {
        return "Viewing Member";
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString("<h5 class='text-sm font-bold'>Member's Name: {$this->record->full_name}</h5>");
    }
}
