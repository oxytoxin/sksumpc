<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus')->iconPosition(IconPosition::After)->visible(auth()->user()->can('manage members')),
            Action::make('generate report')->url(fn ($livewire) => route('filament.app.resources.members.report', [
                'filters' => http_build_query(collect($livewire->tableFilters)->flatMap(fn ($f, $k) => [$k => array_key_exists('value', $f) ? $f['value'] : $f[$k] ?? null])->toArray())
            ]), true)
        ];
    }
}
