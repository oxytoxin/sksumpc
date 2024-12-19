<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCreditAndBackgroundInvestigation
 */
class CreditAndBackgroundInvestigation extends Model
{
    use HasFactory;

    protected $casts = [
        'details' => 'array'
    ];

    public function loan_application()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
