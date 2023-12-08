<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCashCollectible
 */
class CashCollectible extends Model
{
    use HasFactory;

    public function payments(): HasMany
    {
        return $this->hasMany(CashCollectiblePayment::class);
    }

    public function cash_collectible_category(): BelongsTo
    {
        return $this->belongsTo(CashCollectibleCategory::class);
    }
}
