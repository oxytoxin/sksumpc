<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperImprest
 */
class Imprest extends Model
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
}
