<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Member;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;

class MembersReport extends Page
{
    protected static string $resource = MemberResource::class;

    protected string $view = 'filament.app.resources.member-resource.pages.members-report';

    public $filters = [];

    #[Computed]
    public function members()
    {
        $members = Member::query()
            ->when(filled($this->filters['tableFilters']['member_type']['value'] ?? null), fn ($q) => $q->whereMemberTypeId($this->filters['tableFilters']['member_type']['value']))
            ->when(filled($this->filters['tableFilters']['member_subtype']['value'] ?? null), fn ($q) => $q->whereMemberSubtypeId($this->filters['tableFilters']['member_subtype']['value']))
            ->when(filled($this->filters['tableFilters']['patronage_status']['value'] ?? null), fn ($q) => $q->wherePatronageStatusId($this->filters['tableFilters']['patronage_status']['value']))
            ->when(filled($this->filters['tableFilters']['gender']['value'] ?? null), fn ($q) => $q->whereGenderId($this->filters['tableFilters']['gender']['value']))
            ->when(filled($this->filters['tableFilters']['division']['value'] ?? null), fn ($q) => $q->whereDivisionId($this->filters['tableFilters']['division']['value']))
            ->when(filled($this->filters['tableFilters']['membership_date']['value'] ?? null), fn ($q) => $q->whereBetween('members.membership_date', collect(explode(' - ', $this->filters['tableFilters']['membership_date']['value']))->map(fn ($d) => Carbon::createFromFormat('d/m/Y', $d)->format('Y-m-d'))->toArray()))
            ->when(($this->filters['tableFilters']['status']['value'] ?? null) == 1, fn ($q) => $q->whereNull('terminated_at'))
            ->when(($this->filters['tableFilters']['status']['value'] ?? null) == 2, fn ($q) => $q->whereNotNull('terminated_at'))
            ->when($this->filters['tableSortColumn'] ?? null, fn ($q, $v) => $q->orderBy(
                match ($this->filters['tableSortColumn'] ?? null) {
                    'gender.name' => 'gender_name',
                    'division.name' => 'division_name',
                    'member_type.name' => 'member_type_name',
                    default => $this->filters['tableSortColumn']
                }, $this->filters['tableSortDirection']
            ))
            ->with(['patronage_status', 'civil_status', 'gender', 'member_type', 'division'])
            ->leftJoin('member_types', 'member_types.id', '=', 'members.member_type_id')
            ->leftJoin('genders', 'genders.id', '=', 'members.gender_id')
            ->leftJoin('divisions', 'divisions.id', '=', 'members.division_id')
            ->selectRaw('members.*, member_types.name as member_type_name, genders.name as gender_name, divisions.name as division_name')
            ->get();

        return $members;
    }

    public function mount()
    {
        $this->filters = request()->query('query');
    }
}
