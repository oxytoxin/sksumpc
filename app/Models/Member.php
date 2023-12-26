<?php

namespace App\Models;

use App\Oxytoxin\DTO\MemberDependent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\LaravelData\DataCollection;
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
        'dependents' => DataCollection::class.':'.MemberDependent::class,
        'other_income_sources' => 'array',
        'annual_income' => 'decimal:4',
    ];

    protected static function booted(): void
    {
        static::created(function (Member $member) {
            $member->mpc_code = 'MPCSKSU'.$member->id;
            $member->save();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photo')
            ->singleFile();
    }

    public function patronage_status()
    {
        return $this->belongsTo(PatronageStatus::class);
    }

    public function member_type(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function civil_status(): BelongsTo
    {
        return $this->belongsTo(CivilStatus::class);
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

    public function capital_subscriptions_common(): HasOne
    {
        return $this->hasOne(CapitalSubscription::class)->where('is_common', true)->latestOfMany();
    }

    public function capital_subscription_payments(): HasManyThrough
    {
        return $this->hasManyThrough(CapitalSubscriptionPayment::class, CapitalSubscription::class);
    }

    public function initial_capital_subscription(): HasOne
    {
        return $this->hasOne(CapitalSubscription::class)->where('code', 'ICS-%');
    }

    public function savings_accounts()
    {
        return $this->hasMany(SavingsAccount::class);
    }

    public function savings(): HasMany
    {
        return $this->hasMany(Saving::class);
    }

    public function savings_no_interest(): HasMany
    {
        return $this->hasMany(Saving::class)->whereNull('interest_date');
    }

    public function imprests(): HasMany
    {
        return $this->hasMany(Imprest::class);
    }

    public function imprests_no_interest(): HasMany
    {
        return $this->hasMany(Imprest::class)->whereNull('interest_date');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function loan_applications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
