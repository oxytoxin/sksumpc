<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCashBeginning
 */
class CashBeginning extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'transaction_date' => 'immutable_date',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
