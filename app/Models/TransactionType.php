<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionType extends Model
{
    use HasFactory;

    public static function CRJ()
    {
        return self::firstWhere('name', 'CRJ');
    }

    public static function CDJ()
    {
        return self::firstWhere('name', 'CDJ');
    }

    public static function JEV()
    {
        return self::firstWhere('name', 'JEV');
    }
}
