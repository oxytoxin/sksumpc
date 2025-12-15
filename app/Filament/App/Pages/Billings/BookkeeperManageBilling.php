<?php

namespace App\Filament\App\Pages\Billings;

use Illuminate\Support\Facades\Auth;

class BookkeeperManageBilling extends ManageBillings
{
    protected static string | \UnitEnum | null $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage bookkeeping');
    }
}
