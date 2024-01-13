<?php

namespace App\Models;

use App\Oxytoxin\SavingsProvider;
use App\Oxytoxin\TimeDepositsProvider;
use DB;
use Illuminate\Database\Eloquent\Builder;
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
        'balance' => 'decimal:4',
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

    public function savings_account()
    {
        return $this->belongsTo(SavingsAccount::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(function (Builder $q) {
            return $q->addSelect(DB::raw("
                *, 
                DATEDIFF(COALESCE(LEAD(transaction_date) OVER (ORDER BY transaction_date), '".today()->format('Y-m-d')."'), transaction_date) as days_till_next_transaction,
                DATEDIFF(transaction_date, COALESCE(LAG(transaction_date) OVER (ORDER BY transaction_date), transaction_date)) as days_since_last_transaction
            "));
        });

        static::creating(function (Saving $saving) {
            $saving->cashier_id = auth()->id();
        });

        static::created(function (Saving $saving) {
            $prefix = match ($saving->reference_number) {
                SavingsProvider::FROM_TRANSFER_CODE => 'ST-',
                TimeDepositsProvider::FROM_TRANSFER_CODE => 'TD-',
                default => null
            };

            if (! $prefix && $saving->amount < 0) {
                $prefix = 'SW';
            }

            if ($prefix) {
                $saving->reference_number = str($prefix)->append(today()->format('Y').'-')->append(str_pad($saving->id, 6, '0', STR_PAD_LEFT));
            }

            $saving->save();
        });
    }
}
