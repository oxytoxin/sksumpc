<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceForwardedEntry extends Model
{
    use HasFactory;
    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
    ];

    public function balance_forwarded()
    {
        return $this->belongsTo(BalanceForwardedSummary::class);
    }
}
