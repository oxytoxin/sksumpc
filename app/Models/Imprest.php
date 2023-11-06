<?php

namespace App\Models;

use App\Oxytoxin\ImprestsProvider;
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
        'amount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'withdrawal' => 'decimal:2',
        'number_of_days' => 'integer',
        'interest_rate' => 'decimal:4',
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


    protected static function booted()
    {
        static::creating(function (Imprest $imprest) {
            $imprest->cashier_id = auth()->id();
        });

        Imprest::created(function (Imprest $imprest) {
            if ($imprest->reference_number == ImprestsProvider::FROM_TRANSFER_CODE) {
                $imprest->reference_number = str('IT-')->append(today()->format('Y') . '-')->append(str_pad($imprest->id, 6, '0', STR_PAD_LEFT));
            } else {
                if ($imprest->amount < 0) {
                    $imprest->reference_number = str('IW-')->append(today()->format('Y') . '-')->append(str_pad($imprest->id, 6, '0', STR_PAD_LEFT));
                }
            }

            $imprest->save();
        });
    }
}
