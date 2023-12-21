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
        'minimum_initial_payment' => 'decimal:4',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
