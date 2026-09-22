<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class OverdueMaturedLoanReport extends Page
{
    protected static string|\UnitEnum|null $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Overdue / Matured Loans';

    protected static ?string $title = 'Overdue / Matured Loan Report';

    protected string $view = 'filament.app.pages.overdue-matured-loan-report';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->canAny(['manage loans', 'manage bookkeeping']);
    }
}
