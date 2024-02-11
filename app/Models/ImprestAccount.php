<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperImprestAccount
 */
class ImprestAccount extends Account
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_imprest_savings', function ($query) {
            $query->whereNotNull('member_id')->whereTag('imprest_savings');
        });
    }
}
