<?php

namespace App\Oxytoxin\Providers;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class OverrideProvider
{
    public static function promptManagerPasskey($password): bool
    {
        $manager_passkeys = User::whereRelation('roles', 'name', 'manager')->pluck('password');
        $passed = false;
        foreach ($manager_passkeys as $key => $passkey) {
            if (Hash::check($password, $passkey)) {
                $passed = true;
            }
        }
        if (!$passed) {
            Notification::make()->title('Incorrect password.')->danger()->send();

            return false;
        }

        return true;
    }
}
