<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceForwardedSummary extends Model
{
    use HasFactory;

    protected $casts = [
        'generation_date' => 'immutable_date'
    ];

    public function balance_forwarded_entries()
    {
        return $this->hasMany(BalanceForwardedEntry::class);
    }
}