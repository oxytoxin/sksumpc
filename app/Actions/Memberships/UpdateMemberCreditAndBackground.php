<?php

namespace App\Actions\Memberships;

use App\Data\ChildData;
use App\Data\EmploymentVerificationData;
use App\Data\IncomeVerificationData;
use App\Data\SpouseData;
use App\Models\Member;
use App\Models\MemberCreditAndBackground;

class UpdateMemberCreditAndBackground
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Member $member, array $data): MemberCreditAndBackground
    {
        $spouseData = new SpouseData(
            name: $data['spouse_name'] ?? null,
            nickname: $data['spouse_nickname'] ?? null,
            middle_name: $data['spouse_middle_name'] ?? null,
            date_of_birth: $data['spouse_date_of_birth'] ?? null,
            age: $this->nullableInt($data['spouse_age'] ?? null),
            contact_number: $data['spouse_contact_number'] ?? null,
            civil_status: $data['spouse_civil_status'] ?? null,
            nationality: $data['spouse_nationality'] ?? null,
            address: $data['spouse_address'] ?? null,
            highest_educational_attainment: $data['spouse_highest_educational_attainment'] ?? null,
            school: $data['spouse_school'] ?? null,
        );

        $childrenData = collect($data['children'] ?? [])
            ->map(fn (array $child): ChildData => new ChildData(
                name: $child['name'] ?? null,
                birthdate: $child['birthdate'] ?? null,
                course_and_school: $child['course_and_school'] ?? null,
            ))
            ->toArray();

        $employmentVerificationData = new EmploymentVerificationData(
            employer: $data['employer'] ?? null,
            office_address: $data['office_address'] ?? null,
            business_form: $data['business_form'] ?? null,
            nature_of_business: $data['nature_of_business'] ?? null,
            year_connected: $this->nullableInt($data['year_connected'] ?? null),
            position: $data['position'] ?? null,
            employment_status: $data['employment_status'] ?? null,
        );

        $incomeVerificationData = new IncomeVerificationData(
            basic_salary: $this->nullableFloat($data['basic_salary'] ?? null),
            allowances: $this->nullableFloat($data['allowances'] ?? null),
            business_income: $this->nullableFloat($data['business_income'] ?? null),
            other_income: $this->nullableFloat($data['other_income'] ?? null),
            monthly_income: $this->nullableFloat($data['monthly_income'] ?? null),
            annual_income: $this->nullableFloat($data['annual_income'] ?? null),
        );

        return $member->credit_and_background()->updateOrCreate(
            ['member_id' => $member->id],
            [
                'nickname' => $data['nickname'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'school' => $data['school'] ?? null,
                'spouse' => $spouseData,
                'children' => $childrenData,
                'employment_verification' => $employmentVerificationData,
                'income_verification' => $incomeVerificationData,
            ],
        );
    }

    private function nullableInt(mixed $value): ?int
    {
        return filled($value) ? (int) $value : null;
    }

    private function nullableFloat(mixed $value): ?float
    {
        return filled($value) ? (float) $value : null;
    }
}
