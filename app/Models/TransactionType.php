<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransactionType
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
