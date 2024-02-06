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

    protected $casts = [
        'has_inventory' => 'boolean',
    ];

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
            $account_receivables = Account::firstWhere('tag', 'account_receivables');
            Account::create([
                'account_type_id' => $account_receivables->account_type_id,
                'name' => strtoupper($cashCollectible->name),
                'number' => str($account_receivables->number)->append('-')->append(mb_str_pad($cashCollectible->id, 3, '0', STR_PAD_LEFT)),
                'accountable_type' => CashCollectible::class,
                'accountable_id' => $cashCollectible->id,
            ]);
            if ($cashCollectible->has_inventory) {
                $merchandise_inventory = Account::firstWhere('tag', 'merchandise_inventory');
                Account::create([
                    'account_type_id' => $merchandise_inventory->account_type_id,
                    'name' => strtoupper($cashCollectible->name),
                    'number' => str($merchandise_inventory->number)->append('-')->append(mb_str_pad($cashCollectible->id, 3, '0', STR_PAD_LEFT)),
                    'accountable_type' => CashCollectible::class,
                    'accountable_id' => $cashCollectible->id,
                ]);
            }
        });
    }
}
