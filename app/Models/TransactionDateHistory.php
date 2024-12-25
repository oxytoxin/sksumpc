<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransactionDateHistory
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
