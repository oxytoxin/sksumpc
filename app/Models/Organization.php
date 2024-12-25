<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperOrganization
 */
class Organization extends Member
{
    use HasFactory;

    protected $table = 'members';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('organization_members', function ($query) {
            $query->where('is_organization', true);
        });
    }
}
