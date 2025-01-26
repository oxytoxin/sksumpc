<?php

namespace App\Models;

use App\Enums\CashEquivalentsTag;
use App\Enums\MsoTransactionTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransaction
 */
class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
        'transaction_date' => 'immutable_datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function scopeWithoutMso(Builder $query)
    {
        $query->where(function ($query) {
            $query->whereNotIn('tag', MsoTransactionTag::get())
                ->orWhereNull('tag');
        });
    }

    public function scopeWithoutCashEquivalents(Builder $query)
    {
        $query->where(function ($query) {
            $query->whereNotIn('tag', CashEquivalentsTag::get())
                ->orWhereNull('tag');
        });
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            if (blank($transaction->payee)) {
                $transaction->payee = $transaction->member?->full_name ?? 'SKSU-MPC';
            }
        });
    }
}
