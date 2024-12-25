<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMsoBilling
 */
class MsoBilling extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'posted' => 'boolean',
        'for_or' => 'boolean',
    ];

    public function generateReferenceNumber(self $msoBilling)
    {
        return 'MSOBILLING'.'-'.today()->format('Y-m-').str_pad($msoBilling->id, 6, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::created(function (MsoBilling $msoBilling) {
            $msoBilling->reference_number = $msoBilling->generateReferenceNumber($msoBilling);
            $msoBilling->cashier_id = auth()->id();
            $msoBilling->save();
        });
    }

    public function payments()
    {
        return $this->hasMany(MsoBillingPayment::class);
    }
}
