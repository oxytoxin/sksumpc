<?php

namespace App\Filament\App\Pages\Billings;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\App\Pages\Billings\ManageBillings;

class BookkeeperManageBilling extends ManageBillings
{
    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage bookkeeping');
    }
}
