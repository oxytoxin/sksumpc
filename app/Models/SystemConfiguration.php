<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property array<array-key, mixed> $content
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereUpdatedAt($value)
 * @mixin \Eloquent
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
