<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCashCollectibleCategory
 */
class CashCollectibleCategory extends Model
{
    use HasFactory;

    public function cash_collectibles(): HasMany
    {
        return $this->hasMany(CashCollectible::class);
    }
}
