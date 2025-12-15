<?php

namespace App\Filament\App\Pages\Billings;

use Illuminate\Support\Facades\Auth;

class LoanOfficerManageBilling extends ManageBillings
{
    protected static string | \UnitEnum | null $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage loans');
    }
}
