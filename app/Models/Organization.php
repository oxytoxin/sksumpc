<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
 * @property-read \App\Models\CapitalSubscription|null $active_capital_subscription
 * @property-read \App\Models\Account|null $capital_subscription_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $capital_subscription_payments
 * @property-read int|null $capital_subscription_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscription> $capital_subscriptions
 * @property-read int|null $capital_subscriptions_count
 * @property-read \App\Models\CivilStatus|null $civil_status
 * @property-read \App\Models\Division|null $division
 * @property-read \App\Models\Gender|null $gender
 * @property-read mixed $age
 * @property-read mixed $members
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
 * @property-read \App\Models\Occupation|null $occupation
 * @property-read \App\Models\Religion|null $religion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings
 * @property-read int|null $savings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_no_interest
 * @property-read int|null $savings_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_unaccrued
 * @property-read int|null $savings_unaccrued_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAltFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereBarangayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCivilStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereIsOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMemberIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMemberSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMembershipDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMpcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereOccupationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereOtherIncomeSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePatronageStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePresentEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereUserId($value)
 * @mixin \Eloquent
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
