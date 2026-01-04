<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property int $is_current
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionDateHistory extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_datetime',
    ];

    public static function current_date()
    {
        return self::firstWhere('is_current', true)?->date ?? null;
    }
}
