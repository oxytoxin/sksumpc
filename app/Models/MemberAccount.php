<?php

namespace App\Models;

/**
 * @mixin IdeHelperMemberAccount
 */
class MemberAccount extends Account
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('belongsToMember', function ($query) {
            $query->whereNotNull('member_id');
        });
    }
}
