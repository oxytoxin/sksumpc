<?php

    namespace App\Models;

    use App\Observers\MemberObserver;
    use App\Oxytoxin\DTO\Membership\MemberDependent;
    use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
 * @property int $id
 * @property string|null $mpc_code
 * @property array<array-key, mixed> $member_ids
 * @property int $is_organization
 * @property int|null $member_type_id
 * @property int|null $member_subtype_id
 * @property int|null $division_id
 * @property string|null $section
 * @property string|null $grade
 * @property int|null $user_id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $middle_initial
 * @property string|null $alt_full_name
 * @property string|null $full_name
 * @property string|null $tin
 * @property int|null $gender_id
 * @property int|null $civil_status_id
 * @property int $patronage_status_id
 * @property \Carbon\CarbonImmutable $membership_date
 * @property string|null $contact
 * @property \Carbon\CarbonImmutable|null $dob
 * @property string|null $place_of_birth
 * @property string|null $address
 * @property int|null $occupation_id
 * @property string|null $occupation_description
 * @property string|null $present_employer
 * @property string|null $highest_educational_attainment
 * @property \Spatie\LaravelData\DataCollection $dependents
 * @property int|null $dependents_count
 * @property int|null $religion_id
 * @property numeric|null $annual_income
 * @property array<array-key, mixed>|null $other_income_sources
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int|null $municipality_id
 * @property int|null $barangay_id
 * @property string|null $terminated_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\CapitalSubscription|null $active_capital_subscription
 * @property-read \App\Models\Barangay|null $barangay
 * @property-read \App\Models\Account|null $capital_subscription_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $capital_subscription_payments
 * @property-read int|null $capital_subscription_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscription> $capital_subscriptions
 * @property-read int|null $capital_subscriptions_count
 * @property-read \App\Models\CivilStatus|null $civil_status
 * @property-read \App\Models\Division|null $division
 * @property-read \App\Models\Account|null $existing_capital_subscription_account
 * @property-read \App\Models\Gender|null $gender
 * @property-read mixed $age
 * @property-read mixed $members
 * @property-read \App\Models\ImprestAccount|null $imprest_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests
 * @property-read int|null $imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_no_interest
 * @property-read int|null $imprests_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_unaccrued
 * @property-read int|null $imprests_unaccrued_count
 * @property-read \App\Models\CapitalSubscription|null $initial_capital_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanApplication> $loan_applications
 * @property-read int|null $loan_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @property-read \App\Models\LoveGiftAccount|null $love_gift_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts
 * @property-read int|null $love_gifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts_no_interest
 * @property-read int|null $love_gifts_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts_unaccrued
 * @property-read int|null $love_gifts_unaccrued_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\MemberSubtype|null $member_subtype
 * @property-read \App\Models\MemberType|null $member_type
 * @property-read \App\Models\MembershipStatus|null $membership_acceptance
 * @property-read \App\Models\MembershipStatus|null $membership_termination
 * @property-read \App\Models\Municipality|null $municipality
 * @property-read \App\Models\Occupation|null $occupation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfficersList> $officers_list
 * @property-read int|null $officers_list_count
 * @property-read \App\Models\PatronageStatus $patronage_status
 * @property-read \App\Models\Province|null $province
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\Religion|null $religion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings
 * @property-read int|null $savings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $savings_accounts
 * @property-read int|null $savings_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_no_interest
 * @property-read int|null $savings_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_unaccrued
 * @property-read int|null $savings_unaccrued_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAltFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereBarangayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCivilStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereIsOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMemberIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMemberSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMembershipDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMpcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereOccupationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereOtherIncomeSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member wherePatronageStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member wherePresentEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereUserId($value)
 * @mixin \Eloquent
 */
    #[ObservedBy(MemberObserver::class)]
    class Member extends Model implements HasMedia
    {
        use HasFactory, InteractsWithMedia;

        protected $casts = [
            'dob' => 'immutable_date',
            'membership_date' => 'immutable_date',
            'dependents' => DataCollection::class.':'.MemberDependent::class,
            'other_income_sources' => 'array',
            'annual_income' => 'decimal:2',
            'member_ids' => 'array',
            'member_type_id' => 'integer'
        ];

        protected static function booted(): void
        {
            static::created(function (Member $member) {
                if (!$member->mpc_code) {
                    $member->mpc_code = str('SKSUMPC-0000-')->append(str_pad($member->id, 6, '0', STR_PAD_LEFT));
                }
                $member->save();
            });
        }

        public function getMembersAttribute()
        {
            return Member::findMany($this->member_ids);
        }

        public function accounts()
        {
            return $this->hasMany(Account::class);
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

        public function member_subtype(): BelongsTo
        {
            return $this->belongsTo(MemberSubtype::class);
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

        public function capital_subscription_account(): HasOne
        {
            return match ($this->member_type_id) {
                1 => $this->hasOne(Account::class)->whereTag('member_regular_cbu_paid'),
                2 => $this->hasOne(Account::class)->whereTag('member_preferred_cbu_paid'),
                3 => $this->hasOne(Account::class)->whereTag('member_laboratory_cbu_paid'),
                default => $this->hasOne(Account::class)->whereTag('member_regular_cbu_paid'),
            };
        }

        public function existing_capital_subscription_account()
        {
            return $this->hasOne(Account::class)->whereIn('tag', [
                'member_regular_cbu_paid',
                'member_preferred_cbu_paid',
                'member_laboratory_cbu_paid',
            ]);
        }

        public function getAgeAttribute()
        {
            return $this->dob?->diffInYears(today());
        }

        public function active_capital_subscription(): HasOne
        {
            return $this->hasOne(CapitalSubscription::class)->where('is_active', true)->latestOfMany();
        }

        public function capital_subscription_payments(): HasManyThrough
        {
            return $this->hasManyThrough(CapitalSubscriptionPayment::class, CapitalSubscription::class);
        }

        public function initial_capital_subscription(): HasOne
        {
            return $this->hasOne(CapitalSubscription::class)->oldestOfMany();
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

        public function savings_unaccrued(): HasMany
        {
            return $this->hasMany(Saving::class)->where('accrued', false);
        }

        public function imprest_account()
        {
            return $this->hasOne(ImprestAccount::class);
        }

        public function love_gift_account()
        {
            return $this->hasOne(LoveGiftAccount::class);
        }

        public function imprests(): HasMany
        {
            return $this->hasMany(Imprest::class);
        }

        public function imprests_no_interest(): HasMany
        {
            return $this->hasMany(Imprest::class)->whereNull('interest_date');
        }

        public function imprests_unaccrued(): HasMany
        {
            return $this->hasMany(Imprest::class)->where('accrued', false);
        }

        public function love_gifts(): HasMany
        {
            return $this->hasMany(LoveGift::class);
        }

        public function love_gifts_no_interest(): HasMany
        {
            return $this->hasMany(LoveGift::class)->whereNull('interest_date');
        }

        public function love_gifts_unaccrued(): HasMany
        {
            return $this->hasMany(LoveGift::class)->where('accrued', false);
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

        public function officers_list()
        {
            return $this->belongsToMany(OfficersList::class);
        }
    }
