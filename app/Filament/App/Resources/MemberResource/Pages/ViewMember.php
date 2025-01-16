<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Viewing Member';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString("<h5 class='text-sm font-bold'>Member's Name: {$this->record->alt_full_name}</h5>");
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('membership')
                ->extraAttributes(['wire:ignore' => true])
                ->label('Back to Membership Module')
                ->color('success')
                ->url(route('filament.app.resources.members.index')),
        ];
    }
}
