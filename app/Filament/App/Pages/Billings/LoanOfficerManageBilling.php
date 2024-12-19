<?php

namespace App\Filament\App\Pages\Billings;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\App\Pages\Billings\ManageBillings;

class LoanOfficerManageBilling extends ManageBillings
{
    protected static ?string $navigationGroup = 'Loan';
    protected static ?int $navigationSort = 5;
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage loans');
    }
}
