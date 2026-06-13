<?php

namespace App\Actions\Memberships;

use App\Data\ChildData;
use App\Models\Member;
use App\Models\MemberCreditAndBackground;

class UpdateMemberCreditAndBackground
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Member $member, array $data): MemberCreditAndBackground
    {
        $childrenData = collect($data['children'] ?? [])
            ->map(fn (array $child): ChildData => new ChildData(
                name: $child['name'] ?? null,
                birthdate: $child['birthdate'] ?? null,
                course_and_school: $child['course_and_school'] ?? null,
            ))
            ->toArray();

        return $member->credit_and_background()->updateOrCreate(
            ['member_id' => $member->id],
            [
                'nickname' => $data['nickname'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'school' => $data['school'] ?? null,
                'civil_status_id' => $data['civil_status_id'] ?? null,
                'occupation_id' => $data['occupation_id'] ?? null,
                'occupation_description' => $data['occupation_description'] ?? null,
                'present_employer' => $data['present_employer'] ?? null,
                'highest_educational_attainment' => $data['highest_educational_attainment'] ?? null,
                'annual_income' => filled($data['annual_income'] ?? null) ? (float) $data['annual_income'] ?? null : null,
                'monthly_salary' => filled($data['monthly_salary'] ?? null) ? (float) $data['monthly_salary'] ?? null : null,
                'other_income_sources' => $data['other_income_sources'] ?? null,
                'dependents' => $data['dependents'] ?? null,
                'spouse_name' => $data['spouse_name'] ?? null,
                'spouse_nickname' => $data['spouse_nickname'] ?? null,
                'spouse_middle_name' => $data['spouse_middle_name'] ?? null,
                'spouse_date_of_birth' => $data['spouse_date_of_birth'] ?? null,
                'spouse_age' => filled($data['spouse_age'] ?? null) ? (int) $data['spouse_age'] ?? null : null,
                'spouse_contact_number' => $data['spouse_contact_number'] ?? null,
                'spouse_civil_status' => $data['spouse_civil_status'] ?? null,
                'spouse_nationality' => $data['spouse_nationality'] ?? null,
                'spouse_address' => $data['spouse_address'] ?? null,
                'spouse_highest_educational_attainment' => $data['spouse_highest_educational_attainment'] ?? null,
                'spouse_school' => $data['spouse_school'] ?? null,
                'employer' => $data['employer'] ?? null,
                'office_address' => $data['office_address'] ?? null,
                'business_form' => $data['business_form'] ?? null,
                'nature_of_business' => $data['nature_of_business'] ?? null,
                'year_connected' => filled($data['year_connected'] ?? null) ? (int) $data['year_connected'] ?? null : null,
                'position' => $data['position'] ?? null,
                'employment_status' => $data['employment_status'] ?? null,
                'basic_salary' => filled($data['basic_salary'] ?? null) ? (float) $data['basic_salary'] ?? null : null,
                'allowances' => filled($data['allowances'] ?? null) ? (float) $data['allowances'] ?? null : null,
                'business_income' => filled($data['business_income'] ?? null) ? (float) $data['business_income'] ?? null : null,
                'other_income' => filled($data['other_income'] ?? null) ? (float) $data['other_income'] ?? null : null,
                'monthly_income' => filled($data['monthly_income'] ?? null) ? (float) $data['monthly_income'] ?? null : null,
                'children' => $childrenData,
            ],
        );
    }
}
