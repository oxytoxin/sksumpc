<?php

namespace App\Models;

use App\Oxytoxin\SavingsProvider;
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
        'balance' => 'decimal:2',
        'amount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'withdrawal' => 'decimal:2',
        'number_of_days' => 'integer',
        'interest_rate' => 'decimal:2',
        'interest' => 'decimal:2',
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
        static::creating(function (Saving $saving) {
            $saving->cashier_id = auth()->id();
        });

        static::created(function (Saving $saving) {
            if ($saving->reference_number == SavingsProvider::FROM_TRANSFER_CODE) {
                $saving->reference_number = str('ST-')->append(today()->format('Y') . '-')->append(str_pad($saving->id, 6, '0', STR_PAD_LEFT));
            } else {
                if ($saving->amount < 0) {
                    $saving->reference_number = str('SW-')->append(today()->format('Y') . '-')->append(str_pad($saving->id, 6, '0', STR_PAD_LEFT));
                }
            }
            $saving->save();
        });
    }
}
