<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property int $member_type_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_initial
 * @property string|null $membership_number
 * @property string|null $tin
 * @property string $gender
 * @property string $civil_status
 * @property \Carbon\CarbonImmutable|null $dob
 * @property int|null $age
 * @property string|null $address
 * @property int|null $occupation_id
 * @property string|null $highest_educational_attainment
 * @property array $dependents
 * @property int|null $dependents_count
 * @property int|null $religion_id
 * @property string|null $annual_income
 * @property \Carbon\CarbonImmutable|null $accepted_at
 * @property string|null $acceptance_bod_resolution
 * @property \Carbon\CarbonImmutable|null $terminated_at
 * @property string|null $termination_bod_resolution
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MemberType $member_type
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAcceptanceBodResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCivilStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMembershipNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTerminationBodResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMember {}
}

namespace App\Models{
/**
 * App\Models\MemberType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMemberType {}
}

namespace App\Models{
/**
 * App\Models\Occupation
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperOccupation {}
}

namespace App\Models{
/**
 * App\Models\Religion
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Religion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperReligion {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

