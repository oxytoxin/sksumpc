<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    use HasFactory;

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
