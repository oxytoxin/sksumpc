<?php

namespace App\Filament\App\Pages\Billings;

use Illuminate\Support\Facades\Auth;

class CashierManageBilling extends ManageBillings
{
    protected static string | \UnitEnum | null $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage payments');
    }
}
