<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Member;
use Filament\Resources\Pages\Page;

class PrintLoanHistory extends Page
{
    protected static string $resource = MemberResource::class;

    public Member $member;

    protected string $view = 'filament.app.resources.member-resource.pages.print-loan-history';
}
