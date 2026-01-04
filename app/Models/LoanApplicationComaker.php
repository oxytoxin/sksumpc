<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $loan_application_id
 * @property int $member_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\LoanApplication $loan_application
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LoanApplicationComaker extends Pivot
{
    use HasFactory;

    public function loan_application()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
