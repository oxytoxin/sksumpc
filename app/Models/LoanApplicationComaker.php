<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
