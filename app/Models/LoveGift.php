<?php

namespace App\Models;

use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @mixin IdeHelperLoveGift
 */
class LoveGift extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'deposit' => 'decimal:4',
        'withdrawal' => 'decimal:4',
        'number_of_days' => 'integer',
        'interest_rate' => 'decimal:4',
        'interest' => 'decimal:4',
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
        static::addGlobalScope(function (Builder $q) {
            return $q->addSelect(DB::raw("
                *, 
                DATEDIFF(COALESCE(LEAD(transaction_date) OVER (ORDER BY transaction_date), '" . today()->format('Y-m-d') . "'), transaction_date) as days_till_next_transaction,
                DATEDIFF(transaction_date, COALESCE(LAG(transaction_date) OVER (ORDER BY transaction_date), transaction_date)) as days_since_last_transaction
            "));
        });

        static::creating(function (LoveGift $loveGift) {
            $loveGift->cashier_id = auth()->id();
        });

        static::created(function (LoveGift $loveGift) {
            $prefix = match ($loveGift->reference_number) {
                LoveGiftProvider::FROM_TRANSFER_CODE => 'LGT-',
                LoveGiftProvider::WITHDRAWAL_TRANSFER_CODE => 'LGW-',
                LoveGiftProvider::DEPOSIT_TRANSFER_CODE => 'LGD-',
                TimeDepositsProvider::FROM_TRANSFER_CODE => 'TD-',
                default => null
            };

            if ($prefix) {
                $loveGift->reference_number = str($prefix)->append(today()->format('Y') . '-')->append(str_pad($loveGift->id, 6, '0', STR_PAD_LEFT));
            }

            $loveGift->save();
        });
    }

    public function revolving_fund()
    {
        return $this->morphOne(RevolvingFund::class, 'withdrawable');
    }
}
