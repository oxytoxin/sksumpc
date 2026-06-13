<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\DataCollection;

class MemberCreditAndBackground extends Model
{
    protected $table = 'member_credit_and_backgrounds';

    protected $casts = [
        'children' => DataCollection::class.':'.\App\Data\ChildData::class,
        'dependents' => DataCollection::class.':'.\App\Oxytoxin\DTO\Membership\MemberDependent::class,
        'spouse_date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'business_income' => 'decimal:2',
        'other_income' => 'decimal:2',
        'monthly_income' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function civil_status(): BelongsTo
    {
        return $this->belongsTo(CivilStatus::class);
    }

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }
}
