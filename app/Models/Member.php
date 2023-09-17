<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        'dob' => 'immutable_date',
        'dependents' => 'array',
        'other_income_sources' => 'array',
        'annual_income' => 'decimal:2',
    ];

    protected $attributes = [
        'dependents' => "[]",
        'other_income_sources' => "[]",
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photo')
            ->singleFile();
    }

    public function member_type(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }

    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    public function membership_acceptance(): HasOne
    {
        return $this->hasOne(MembershipStatus::class)->where('type', MembershipStatus::ACCEPTANCE);
    }

    public function membership_termination(): HasOne
    {
        return $this->hasOne(MembershipStatus::class)->where('type', MembershipStatus::TERMINATION);
    }

    public function capital_subscriptions(): HasMany
    {
        return $this->hasMany(CapitalSubscription::class);
    }

    public function initial_capital_subscription(): HasOne
    {
        return $this->hasOne(CapitalSubscription::class)->where('is_ics', true);
    }
}
