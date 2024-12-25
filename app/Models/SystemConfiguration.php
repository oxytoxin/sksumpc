<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSystemConfiguration
 */
class SystemConfiguration extends Model
{
    use HasFactory;

    protected $casts = [
        'content' => 'array',
    ];

    public static function config($key)
    {
        return self::firstWhere('name', $key);
    }

    public static function transaction_date()
    {
        $transaction_date = self::firstWhere('name', 'Transaction Date');
        if (! $transaction_date) {
            return null;
        }

        return CarbonImmutable::create($transaction_date->content['transaction_date']);
    }
}
