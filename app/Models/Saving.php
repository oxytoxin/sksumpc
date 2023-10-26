<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSaving
 */
class Saving extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:2',
        'number_of_days' => 'integer',
        'interest_rate' => 'decimal:2',
        'interest' => 'decimal:2',
        'accrued' => 'boolean',
        'transaction_date' => 'immutable_date',
        'interest_date' => 'immutable_date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }


    protected static function booted()
    {
        static::creating(function (Saving $saving) {
            $saving->cashier_id = auth()->id();
        });
    }
}
