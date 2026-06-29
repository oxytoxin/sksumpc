<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\CreditAndBackgroundInvestigation;
use App\Models\LoanApplication;
use App\Models\Member;
use App\Models\MemberCreditAndBackground;
use App\Models\SignatureSet;
use App\Models\User;
use Carbon\Carbon;
use DateTimeInterface;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Traversable;

class CreditAndBackgroundInvestigationReport extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected string $view = 'filament.app.resources.loan-application-resource.pages.credit-and-background-investigation-report';

    use HasSignatories;

    public CreditAndBackgroundInvestigation $cibi;

    #[Computed]
    public function LoanApplication(): LoanApplication
    {
        return $this->cibi->loan_application;
    }

    #[Computed]
    public function LoanApplicationMember(): Member
    {
        return $this->cibi->loan_application->member;
    }

    public function reportDetails(): array
    {
        $this->cibi->loadMissing([
            'loan_application.member.credit_and_background.civil_status',
            'loan_application.comakers.member.credit_and_background.civil_status',
        ]);

        $details = is_array($this->cibi->details) ? $this->cibi->details : [];

        if ($this->cibi->loan_application->loan_type_id == 5) {
            $details['assets'] = $this->rowsFrom($details['assets'] ?? []);
            $details['existing_structure'] = $this->rowsFrom($details['existing_structure'] ?? []);

            return $details;
        }

        $loanMembers = $this->loanMembers();
        $borrowerCibi = $loanMembers->first()?->credit_and_background;

        $details['borrower'] = $this->mergeDefaults(
            $this->borrowerDefaults($borrowerCibi),
            $details['borrower'] ?? [],
        );

        $details['spouse'] = $this->mergeDefaults(
            $this->spouseDefaults($borrowerCibi),
            $details['spouse'] ?? [],
        );

        $children = $this->rowsFrom($details['children'] ?? []);
        $details['children'] = $children !== []
            ? $children
            : $this->rowsFrom($borrowerCibi?->children ?? []);

        $details['assets'] = $this->rowsFrom($details['assets'] ?? []);

        $details['employment_verification'] = $this->verificationRows(
            $details['employment_verification'] ?? [],
            $loanMembers,
            $this->employmentVerificationMapping(),
        );

        $details['income_verification'] = $this->verificationRows(
            $details['income_verification'] ?? [],
            $loanMembers,
            $this->incomeVerificationMapping(),
        );

        return $details;
    }

    public function reportColumns(array $rows, array $preferredColumns): array
    {
        $columns = $preferredColumns;

        foreach ($rows as $row) {
            foreach (array_keys($this->rowToArray($row)) as $column) {
                if (! in_array($column, $columns, true)) {
                    $columns[] = $column;
                }
            }
        }

        return $columns;
    }

    public function reportColumnLabel(string $column): string
    {
        return match ($column) {
            'coborrower_1' => 'Co-Borrower 1',
            'coborrower_2' => 'Co-Borrower 2',
            'course_and_school' => 'Course/School',
            default => str($column)->replace('_', ' ')->headline()->toString(),
        };
    }

    public function reportValue(array $row, string $column): string
    {
        if ($column === 'age' && blank($row['age'] ?? null) && filled($row['birthdate'] ?? null)) {
            return (string) today()->diffInYears($row['birthdate']);
        }

        return $this->formatReportValue($row[$column] ?? null, $column);
    }

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'CIBI Reports')->first();
    }

    protected function getAdditionalSignatories(): array
    {
        if ($this->cibi->loan_application->desired_amount > $this->cibi->loan_application->loan_type->approval_threshold) {
            $user = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
            $designation = 'BOD-Chairperson';
        } else {
            $user = User::whereRelation('roles', 'name', 'manager')->first();
            $designation = 'Manager';
        }

        return [
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'action' => 'Noted by:',
                'designation' => $designation,
            ],
        ];
    }

    private function loanMembers(): Collection
    {
        $loanApplication = $this->cibi->loan_application;

        return collect([$loanApplication->member])
            ->merge($loanApplication->comakers->pluck('member'))
            ->filter()
            ->values();
    }

    private function borrowerDefaults(?MemberCreditAndBackground $cibi): array
    {
        return [
            'nickname' => $cibi?->nickname,
            'nationality' => $cibi?->nationality,
            'school' => $cibi?->school,
            'civil_status' => $cibi?->civil_status?->name,
            'highest_educational_attainment' => $cibi?->highest_educational_attainment,
        ];
    }

    private function spouseDefaults(?MemberCreditAndBackground $cibi): array
    {
        return [
            'name' => $cibi?->spouse_name,
            'nickname' => $cibi?->spouse_nickname,
            'middle_name' => $cibi?->spouse_middle_name,
            'date_of_birth' => $cibi?->spouse_date_of_birth,
            'age' => $cibi?->spouse_age,
            'contact_number' => $cibi?->spouse_contact_number,
            'civil_status' => $cibi?->spouse_civil_status,
            'nationality' => $cibi?->spouse_nationality,
            'address' => $cibi?->spouse_address,
            'highest_educational_attainment' => $cibi?->spouse_highest_educational_attainment,
            'school' => $cibi?->spouse_school,
        ];
    }

    private function mergeDefaults(array $defaults, mixed $existing): array
    {
        $merged = $this->rowToArray($existing);

        foreach ($defaults as $key => $value) {
            if (! array_key_exists($key, $merged) || blank($merged[$key])) {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    private function employmentVerificationMapping(): array
    {
        return [
            'Employer' => 'present_employer',
            'Office Address' => 'office_address',
            'Business Form' => 'business_form',
            'Nature of Business' => 'nature_of_business',
            'Year Connected' => 'year_connected',
            'Position' => 'position',
            'Status of Employment' => 'employment_status',
        ];
    }

    private function incomeVerificationMapping(): array
    {
        return [
            'Basic Salary' => 'basic_salary',
            'Allowances' => 'allowances',
            'Business Income' => 'business_income',
            'Other Income' => 'other_income',
            'Monthly Income' => 'monthly_income',
            'Annual Income' => 'annual_income',
        ];
    }

    private function verificationRows(mixed $rows, Collection $loanMembers, array $mapping): array
    {
        $savedRows = collect($this->rowsFrom($rows));
        $memberKeys = $this->verificationMemberKeys($loanMembers->count());
        $verificationRows = [];

        foreach ($mapping as $particulars => $column) {
            $rowIndex = $savedRows->search(fn (array $row): bool => ($row['particulars'] ?? null) === $particulars);
            $row = $rowIndex === false ? ['particulars' => $particulars] : $savedRows->pull($rowIndex);
            $row['particulars'] = $particulars;

            foreach ($loanMembers->values() as $index => $member) {
                $memberKey = $memberKeys[$index] ?? null;

                if ($memberKey && (! array_key_exists($memberKey, $row) || blank($row[$memberKey]))) {
                    $row[$memberKey] = $member?->credit_and_background?->{$column};
                }
            }

            $verificationRows[] = $row;
        }

        return [
            ...$verificationRows,
            ...$savedRows->values()->all(),
        ];
    }

    private function verificationMemberKeys(int $memberCount): array
    {
        $keys = ['borrower'];

        for ($index = 1; $index < $memberCount; $index++) {
            $keys[] = 'coborrower_'.$index;
        }

        return $keys;
    }

    private function rowsFrom(mixed $rows): array
    {
        if ($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }

        if ($rows instanceof Traversable) {
            $rows = iterator_to_array($rows);
        }

        if (! is_array($rows)) {
            return [];
        }

        return collect($rows)
            ->map(fn (mixed $row): array => $this->rowToArray($row))
            ->filter(fn (array $row): bool => $row !== [])
            ->values()
            ->all();
    }

    private function rowToArray(mixed $row): array
    {
        if ($row instanceof Arrayable) {
            $row = $row->toArray();
        } elseif ($row instanceof Traversable) {
            $row = iterator_to_array($row);
        } elseif (is_object($row)) {
            $row = get_object_vars($row);
        }

        return is_array($row) ? $row : [];
    }

    private function formatReportValue(mixed $value, string $column): string
    {
        if (blank($value)) {
            return '';
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('m/d/Y');
        }

        if (is_array($value)) {
            return collect($value)
                ->map(fn (mixed $item): string => $this->formatReportValue($item, $column))
                ->filter()
                ->implode(', ');
        }

        if (str_contains($column, 'date') || str_contains($column, 'birthdate')) {
            try {
                return Carbon::parse($value)->format('m/d/Y');
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        return (string) $value;
    }
}
