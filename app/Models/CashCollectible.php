<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $cash_collectible_category_id
 * @property string $name
 * @property bool $has_inventory
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleCategory $cash_collectible_category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectiblePayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereCashCollectibleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereHasInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereUpdatedAt($value)
 * @mixin \Eloquent
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
            $other_income = Account::firstWhere('tag', 'other_income');
            Account::create([
                'account_type_id' => $account_receivables->account_type_id,
                'name' => strtoupper($cashCollectible->name),
                'number' => str($account_receivables->number)->append('-')->append(mb_str_pad($cashCollectible->id, 4, '0', STR_PAD_LEFT)),
                'accountable_type' => CashCollectible::class,
                'accountable_id' => $cashCollectible->id,
                'tag' => 'account_receivables',
            ], $account_receivables);
            Account::create([
                'account_type_id' => $other_income->account_type_id,
                'name' => strtoupper($cashCollectible->name),
                'number' => str($other_income->number)->append('-')->append(mb_str_pad($cashCollectible->id, 4, '0', STR_PAD_LEFT)),
                'accountable_type' => CashCollectible::class,
                'accountable_id' => $cashCollectible->id,
                'tag' => 'other_income',
            ], $other_income);
            if ($cashCollectible->has_inventory) {
                $merchandise_inventory = Account::firstWhere('tag', 'merchandise_inventory');
                Account::create([
                    'account_type_id' => $merchandise_inventory->account_type_id,
                    'name' => strtoupper($cashCollectible->name),
                    'number' => str($merchandise_inventory->number)->append('-')->append(mb_str_pad($cashCollectible->id, 4, '0', STR_PAD_LEFT)),
                    'accountable_type' => CashCollectible::class,
                    'accountable_id' => $cashCollectible->id,
                    'tag' => 'merchandise_inventory',
                ], $merchandise_inventory);
            }
        });
    }
}
