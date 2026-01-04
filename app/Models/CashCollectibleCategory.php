<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectible> $cash_collectibles
 * @property-read int|null $cash_collectibles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CashCollectibleCategory extends Model
{
    use HasFactory;

    public function cash_collectibles(): HasMany
    {
        return $this->hasMany(CashCollectible::class);
    }
}
