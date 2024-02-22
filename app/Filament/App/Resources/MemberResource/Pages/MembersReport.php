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
            ->when(filled($this->filters['member_type'] ?? null), fn ($q) => $q->whereMemberTypeId($this->filters['member_type']))
            ->when(filled($this->filters['patronage_status'] ?? null), fn ($q) => $q->wherePatronageStatusId($this->filters['patronage_status']))
            ->when(filled($this->filters['gender'] ?? null), fn ($q) => $q->whereGenderId($this->filters['gender']))
            ->when(filled($this->filters['created_at'] ?? null), fn ($q) => $q->whereBetween('members.created_at', collect(explode(' - ', $this->filters['created_at']))->map(fn ($d) => Carbon::createFromFormat('d/m/Y', $d)->format('Y-m-d'))->toArray()))
            ->when(($this->filters['status'] ?? null) == 1, fn ($q) => $q->whereNull('terminated_at'))
            ->when(($this->filters['status'] ?? null) == 2, fn ($q) => $q->whereNotNull('terminated_at'))
            ->with(['patronage_status', 'civil_status', 'gender', 'member_type'])
            ->orderBy('last_name')
            ->cursor();
    }

    public function mount()
    {

        $filters = [];
        parse_str(request()->query('filters'), $filters);
        $this->filters = $filters;
    }
}
