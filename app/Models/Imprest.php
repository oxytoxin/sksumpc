<?php

namespace App\Models;

use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperImprest
 */
class Imprest extends Model
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

    public function account()
    {
        return $this->belongsTo(Account::class);
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

        static::creating(function (Imprest $imprest) {
            $imprest->cashier_id = auth()->id();
        });

        Imprest::created(function (Imprest $imprest) {
            $prefix = match ($imprest->reference_number) {
                ImprestsProvider::FROM_TRANSFER_CODE => 'IT-',
                ImprestsProvider::WITHDRAWAL_TRANSFER_CODE => 'IW-',
                TimeDepositsProvider::FROM_TRANSFER_CODE => 'TD-',
                default => null
            };

            if ($prefix) {
                $imprest->reference_number = str($prefix)->append(today()->format('Y') . '-')->append(str_pad($imprest->id, 6, '0', STR_PAD_LEFT));
            }

            $imprest->save();
        });
    }
}
