<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
