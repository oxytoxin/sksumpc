<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Member;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;

class MembersReport extends Page
{
    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.members-report';

    public $filters = [];

    #[Computed]
    public function members()
    {
        return Member::query()
            ->when(($this->filters['member_type'] ?? null), fn ($q) => $q->whereMemberTypeId($this->filters['member_type']))
            ->when(($this->filters['patronage_status'] ?? null), fn ($q) => $q->wherePatronageStatusId($this->filters['patronage_status']))
            ->when(($this->filters['gender'] ?? null), fn ($q) => $q->whereGenderId($this->filters['gender']))
            ->when($this->filters['created_at'] ?? null, fn ($q) => $q->whereBetween('members.created_at', collect(explode(' - ', $this->filters['created_at']))->map(fn ($d) => Carbon::createFromFormat('d/m/Y', $d)->format('Y-m-d'))->toArray()))
            ->when(($this->filters['status'] ?? null) == 1, fn ($q) => $q->whereNull('terminated_at'))
            ->when(($this->filters['status'] ?? null) == 2, fn ($q) => $q->whereNotNull('terminated_at'))
            ->leftJoin('patronage_statuses', 'members.patronage_status_id', 'patronage_statuses.id')
            ->leftJoin('civil_statuses', 'members.civil_status_id', 'civil_statuses.id')
            ->leftJoin('genders', 'members.gender_id', 'genders.id')
            ->leftJoin('member_types', 'members.member_type_id', 'member_types.id')
            ->orderBy('last_name')
            ->select(['members.*', 'patronage_statuses.name as patronage_status_name', 'civil_statuses.name as civil_status_name', 'genders.name as gender_name', 'member_types.name as member_type_name'])
            ->cursor();
    }

    public function mount()
    {

        $filters = [];
        parse_str(request()->query('filters'), $filters);
        $this->filters = $filters;
    }
}
