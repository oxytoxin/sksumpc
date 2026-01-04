<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $loan_application_id
 * @property array<array-key, mixed> $details
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\LoanApplication $loan_application
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CreditAndBackgroundInvestigation extends Model
{
    use HasFactory;

    protected $casts = [
        'details' => 'array',
    ];

    public function loan_application()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
