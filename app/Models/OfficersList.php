<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOfficersList
 */
class OfficersList extends Model
{
    use HasFactory;

    protected $casts = [
        'officers' => 'array',
    ];
}
