<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMemberType
 */
class MemberType extends Model
{
    use HasFactory;

    protected $casts = [
        'default_number_of_shares' => 'decimal:4',
        'default_amount_subscribed' => 'decimal:4',
        'minimum_initial_payment' => 'decimal:4',
        'par_value' => 'decimal:4',
        'surcharge_rate' => 'decimal:4',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
