<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use Filament\Resources\Pages\Page;

class LoanDisclosureSheet extends Page
{
    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.loan-disclosure-sheet';

    public Loan $loan;
}
