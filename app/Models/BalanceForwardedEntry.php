<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBalanceForwardedEntry
 */
class BalanceForwardedEntry extends Model
{
    use HasFactory;
    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
    ];

    public function balance_forwarded_summary()
    {
        return $this->belongsTo(BalanceForwardedSummary::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->tree();
    }
}
