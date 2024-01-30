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

    protected static function booted()
    {
        static::created(function (CashCollectible $cashCollectible) {
            TrialBalanceEntry::create([
                'name' => strtolower($cashCollectible->name),
                'auditable_type' => CashCollectible::class,
                'auditable_id' => $cashCollectible->id,
                'operator' => 1,
                'code' => 11250,
            ])->insertBeforeNode(
                TrialBalanceEntry::firstWhere('name', 'allowance for probable losses-a/r')
            );
            TrialBalanceEntry::create([
                'name' => strtolower($cashCollectible->name),
                'auditable_type' => CashCollectible::class,
                'auditable_id' => $cashCollectible->id,
                'operator' => 1,
                'code' => 11510,
            ])->insertBeforeNode(
                TrialBalanceEntry::firstWhere('name', 'other current assets')
            );
        });
    }
}
