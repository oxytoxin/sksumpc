<?php

namespace App\Filament\App\Pages\Billings;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\App\Pages\Billings\ManageBillings;

class CashierManageBilling extends ManageBillings
{
    protected static ?string $navigationGroup = 'Cashier';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage payments');
    }
}
