<?php

namespace App\Models;

use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
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
        'transaction_datetime' => 'immutable_datetime',
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
                DATEDIFF(COALESCE(LEAD(transaction_date) OVER (ORDER BY transaction_date), '" . today()->format('Y-m-d') . "'), transaction_date) as days_till_next_transaction,
                DATEDIFF(transaction_date, COALESCE(LAG(transaction_date) OVER (ORDER BY transaction_date), transaction_date)) as days_since_last_transaction
            "));
        });

        static::creating(function (Saving $saving) {
            $saving->cashier_id = auth()->id();
        });

        static::created(function (Saving $saving) {
            $prefix = match ($saving->reference_number) {
                SavingsProvider::FROM_TRANSFER_CODE => 'ST-',
                SavingsProvider::WITHDRAWAL_TRANSFER_CODE => 'SW-',
                TimeDepositsProvider::FROM_TRANSFER_CODE => 'TD-',
                default => null
            };

            if ($prefix) {
                $saving->reference_number = str($prefix)->append((config('app.transaction_date') ?? today())->format('Y') . '-')->append(str_pad($saving->id, 6, '0', STR_PAD_LEFT));
            }

            $saving->save();
        });
    }

    public function revolving_fund()
    {
        return $this->morphOne(RevolvingFund::class, 'withdrawable');
    }
}
