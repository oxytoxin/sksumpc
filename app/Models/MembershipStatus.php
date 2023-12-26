<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperMembershipStatus
 */
class MembershipStatus extends Model
{
    use HasFactory;

    protected $casts = [
        'effectivity_date' => 'immutable_date',
    ];

    const ACCEPTANCE = 1;

    const TERMINATION = 2;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
