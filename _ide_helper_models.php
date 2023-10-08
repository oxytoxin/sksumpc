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
 * App\Models\CapitalSubscription
 *
 * @property int $id
 * @property int $member_id
 * @property bool $is_common
 * @property string $code
 * @property int $number_of_terms
 * @property int $number_of_shares
 * @property string $amount_subscribed
 * @property string $initial_amount_paid
 * @property string $outstanding_balance
 * @property string $par_value
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereInitialAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereIsCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscription {}
}

namespace App\Models{
/**
 * App\Models\CapitalSubscriptionPayment
 *
 * @property int $id
 * @property int $capital_subscription_id
 * @property string $type
 * @property string $amount
 * @property string $running_balance
 * @property string|null $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereRunningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscriptionPayment {}
}

namespace App\Models{
/**
 * App\Models\Imprest
 *
 * @property int $id
 * @property int $member_id
 * @property string $reference_number
 * @property string $amount
 * @property string $interest_rate
 * @property string $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property int|null $number_of_days
 * @property string $balance
 * @property bool $accrued
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperImprest {}
}

namespace App\Models{
/**
 * App\Models\Loan
 *
 * @property int $id
 * @property int $member_id
 * @property int $loan_type_id
 * @property string|null $reference_number
 * @property string $gross_amount
 * @property string $deductions_amount
 * @property string|null $net_amount
 * @property array $deductions
 * @property int $number_of_terms
 * @property string $interest_rate
 * @property string $interest
 * @property string $monthly_payment
 * @property string $outstanding_balance
 * @property \Carbon\CarbonImmutable $release_date
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $deductions_list
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDeductionsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereGrossAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoan {}
}

namespace App\Models{
/**
 * App\Models\LoanPayment
 *
 * @property int $id
 * @property int $loan_id
 * @property string $type
 * @property string $amount
 * @property string $running_balance
 * @property string|null $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Loan $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereRunningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanPayment {}
}

namespace App\Models{
/**
 * App\Models\LoanType
 *
 * @property int $id
 * @property string $interest
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanType {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property string $mpc_code
 * @property int $member_type_id
 * @property int|null $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_initial
 * @property string|null $full_name
 * @property string|null $alt_full_name
 * @property string|null $tin
 * @property string|null $gender
 * @property string|null $civil_status
 * @property \Carbon\CarbonImmutable|null $dob
 * @property string|null $place_of_birth
 * @property int|null $age
 * @property string|null $address
 * @property int|null $occupation_id
 * @property string|null $present_employer
 * @property string|null $highest_educational_attainment
 * @property array $dependents
 * @property array $other_income_sources
 * @property int|null $dependents_count
 * @property int|null $religion_id
 * @property string|null $annual_income
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $capital_subscription_payments
 * @property-read int|null $capital_subscription_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscription> $capital_subscriptions
 * @property-read int|null $capital_subscriptions_count
 * @property-read \App\Models\CapitalSubscription|null $capital_subscriptions_common
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests
 * @property-read int|null $imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_no_interest
 * @property-read int|null $imprests_no_interest_count
 * @property-read \App\Models\CapitalSubscription|null $initial_capital_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\MemberType $member_type
 * @property-read \App\Models\MembershipStatus|null $membership_acceptance
 * @property-read \App\Models\MembershipStatus|null $membership_termination
 * @property-read \App\Models\Occupation|null $occupation
 * @property-read \App\Models\Religion|null $religion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings
 * @property-read int|null $savings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_no_interest
 * @property-read int|null $savings_no_interest_count
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAltFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCivilStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMpcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereOtherIncomeSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member wherePresentEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUserId($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
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
 * App\Models\MembershipStatus
 *
 * @property int $id
 * @property int $member_id
 * @property int $type
 * @property string|null $bod_resolution
 * @property \Carbon\CarbonImmutable $effectivity_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereBodResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereEffectivityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMembershipStatus {}
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
 * App\Models\Saving
 *
 * @property int $id
 * @property int $member_id
 * @property string $reference_number
 * @property string $amount
 * @property string $interest_rate
 * @property string $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property int|null $number_of_days
 * @property string $balance
 * @property bool $accrued
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|Saving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Saving newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Saving query()
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSaving {}
}

namespace App\Models{
/**
 * App\Models\SystemConfiguration
 *
 * @property int $id
 * @property string $name
 * @property array $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSystemConfiguration {}
}

namespace App\Models{
/**
 * App\Models\TimeDeposit
 *
 * @property int $id
 * @property int $member_id
 * @property string $reference_number
 * @property \Carbon\CarbonImmutable $maturity_date
 * @property string|null $withdrawal_date
 * @property string $amount
 * @property int $number_of_days
 * @property string $maturity_amount
 * @property string $interest_rate
 * @property string|null $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string $tdc_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereMaturityAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereMaturityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereTdcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereWithdrawalDate($value)
 * @mixin \Eloquent
 */
	class IdeHelperTimeDeposit {}
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

