<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureSet extends Model
{
    use HasFactory;

    public function signatories()
    {
        return $this->hasMany(SignatureSetSignatory::class);
    }
}
