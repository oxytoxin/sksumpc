<x-filament-panels::page>
    {{ $this->form }}
    <x-app.cashier.reports.report-layout :signatories="$signatories">
        <div class="text-center font-bold uppercase">
            <p class="text-right text-sm font-normal">Confidential CIR 1</p>
            <h3>CREDIT AND BACKGROUND INVESTIGATION REPORT</h3>
        </div>
        <table class="w-full">
            <tr>
                <th class="border border-black px-2 text-left">Borrower</th>
                <td class="border border-black px-2 text-left">{{ $cibi->loan_application->member->full_name }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Address</th>
                <td class="border border-black px-2 text-left">{{ $cibi->loan_application->member->address }}</td>
            </tr>
        </table>
        <table class="w-full">
            <tr>
                <th colspan="4" class="border border-black px-2 text-left">A. PERSONAL DATA</th>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">1. Basic Information</th>
                <th class="border border-black px-2 text-left">Borrower</th>
                <th class="border border-black px-2 text-left">Spouse</th>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Name</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->full_name }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['name'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Nickname/Alias</th>
                <td class="border border-black px-2 text-left">{{ $cibi->details['borrower']['nickname'] ?? '' }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['nickname'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Middle Name</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->middle_name }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['middle_name'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Date of Birth</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->dob?->format('m/d/Y') }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['date_of_birth'] ?? null ? date_create($cibi->details['spouse']['date_of_birth'])->format('m/d/Y') : '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Age</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->age }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['age'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Contact No.</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->contact }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['contact_number'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Civil Status</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->civil_status?->name }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['civil_status'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Nationality</th>
                <td class="border border-black px-2 text-left">{{ $cibi->details['borrower']['nationality'] ?? '' }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['nationality'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Address</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->address }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['address'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Highest Educational Attainment</th>
                <td class="border border-black px-2 text-left">{{ $this->loan_application_member->highest_educational_attainment }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['highest_educational_attainment'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">School</th>
                <td class="border border-black px-2 text-left">{{ $cibi->details['borrower']['school'] ?? '' }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->details['spouse']['school'] ?? '' }}</td>
            </tr>
        </table>
        <table class="w-full">
            <tr>
                <th class="border border-black px-2 text-left">2. Name of Children</th>
                <th class="border border-black px-2 text-left">Birthdate</th>
                <th class="border border-black px-2 text-left">Age</th>
                <th class="border border-black px-2 text-left">Course/School</th>
            </tr>
            @forelse ($cibi->details['children'] ?? [] as $child)
                <tr>
                    <th class="border border-black px-2 text-left">{{ $child['name'] ?? '' }}</th>
                    <th class="border border-black px-2 text-left">{{ $child['birthdate'] ?? null ? date_create($child['birthdate'])->format('m/d/Y') : null }}</th>
                    <td class="border border-black px-2 text-left">{{ $child['birthdate'] ?? null ? today()->diffInYears($child['birthdate']) : null }}</td>
                    <td class="border border-black px-2 text-left">{{ $child['course_and_school'] ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border border-black px-2 text-left" colspan="4">No children found.</td>
                </tr>
            @endforelse
        </table>
        <table class="w-full">
            <tr>
                <th class="border border-black px-2 text-left">3. Assets</th>
                <th class="border border-black px-2 text-left">Value</th>
                <th class="border border-black px-2 text-left">Status</th>
            </tr>
            @forelse ($cibi->details['assets'] ?? [] as $asset)
                <tr>
                    <th class="border border-black px-2 text-left">{{ $asset['name'] ?? '' }}</th>
                    <td class="border border-black px-2 text-left">{{ $asset['value'] ?? '' }}</td>
                    <td class="border border-black px-2 text-left">{{ $asset['status'] ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border border-black px-2 text-left" colspan="3">No assets found.</td>
                </tr>
            @endforelse
        </table>
        <table class="w-full">
            <tr>
                <th colspan="4" class="border border-black px-2 text-left">4. Employment Verification</th>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Particulars</th>
                <th class="border border-black px-2 text-left">Borrower</th>
                <th class="border border-black px-2 text-left">Co-Borrower 1</th>
                <th class="border border-black px-2 text-left">Co-Borrower 2</th>
            </tr>
            @foreach ($cibi->details['employment_verification'] ?? [] as $key => $verification_item)
                <tr>
                    <th class="border border-black px-2 text-left">{{ $verification_item['particulars'] }}</th>
                    <td class="border border-black px-2 text-left">{{ $verification_item['borrower'] ?? '' }}</td>
                    <td class="border border-black px-2 text-left">{{ $verification_item['coborrower_1'] ?? '' }}</td>
                    <td class="border border-black px-2 text-left">{{ $verification_item['coborrower_2'] ?? '' }}</td>
                </tr>
            @endforeach
        </table>
        <table class="w-full">
            <tr>
                <th colspan="4" class="border border-black px-2 text-left">5. Income Verification</th>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Particulars</th>
                <th class="border border-black px-2 text-left">Borrower</th>
                <th class="border border-black px-2 text-left">Co-Borrower 1</th>
                <th class="border border-black px-2 text-left">Co-Borrower 2</th>
            </tr>
            @foreach ($cibi->details['income_verification'] ?? [] as $key => $verification_item)
                <tr>
                    <th class="border border-black px-2 text-left">{{ $verification_item['particulars'] }}</th>
                    <td class="border border-black px-2 text-left">{{ $verification_item['borrower'] ?? '' }}</td>
                    <td class="border border-black px-2 text-left">{{ $verification_item['coborrower_1'] ?? '' }}</td>
                    <td class="border border-black px-2 text-left">{{ $verification_item['coborrower_2'] ?? '' }}</td>
                </tr>
            @endforeach
        </table>
        <table class="w-full">
            <tr>
                <th colspan="6" class="border border-black px-2 text-left">6. Nature of Loan</th>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">Type of Loan</th>
                <th class="border border-black px-2 text-left">Date Approved</th>
                <th class="border border-black px-2 text-left">Amount Granted</th>
                <th class="border border-black px-2 text-left">Mode of Payment</th>
                <th class="border border-black px-2 text-left">Credit Terms</th>
                <th class="border border-black px-2 text-left">Outstanding Balance</th>
            </tr>
            <tr>
                <th class="border border-black px-2 text-left">{{ $cibi->loan_application->loan_type->name }}</th>
                <td class="border border-black px-2 text-left">{{ $cibi->loan_application->approval_date?->format('m/d/Y') }}</td>
                <td class="border border-black px-2 text-left">{{ renumber_format($cibi->loan_application->desired_amount, 2) }}</td>
                <td class="border border-black px-2 text-left"></td>
                <td class="border border-black px-2 text-left">{{ $cibi->loan_application->number_of_terms }}</td>
                <td class="border border-black px-2 text-left">{{ $cibi->loan_application->desired_amount }}</td>
            </tr>
            <tr>
                <td colspan="3" class="border border-black px-2 text-left">Date request received: {{ $cibi->loan_application->transaction_date->format('m/d/Y') }}</td>
                <td colspan="3" class="border border-black px-2 text-left">Date of Report: {{ today()->format('m/d/Y') }}</td>
            </tr>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
