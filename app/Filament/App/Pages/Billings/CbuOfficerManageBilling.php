<?php

namespace App\Filament\App\Pages\Billings;

use Auth;

class CbuOfficerManageBilling extends ManageBillings
{
    protected static ?string $navigationGroup = 'Share Capital';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage cbu');
    }
}
