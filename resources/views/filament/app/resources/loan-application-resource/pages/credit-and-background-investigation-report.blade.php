<x-filament-panels::page>
    @if ($this->loan_application->loan_type_id == 5)
        <x-app.cashier.reports.report-layout>
            <div class="print:text-[8pt]">
                <div class="text-center font-bold uppercase">
                    <h3>CREDIT AND BACKGROUND INVESTIGATION REPORT</h3>
                    <p class="font-normal">( for Business Applicant )</p>
                </div>
                <div class="text-sm print:text-[8pt]">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <h4 class="font-bold">I. Personal Information</h4>
                            <div class="px-8">
                                <h5>Name: {{ $this->loan_application_member->full_name }}</h5>
                                <h5>Address: {{ $this->loan_application_member->address }}</h5>
                                <h5>Name of Spouse: {{ $cibi->details['name_of_spouse'] ?? '' }}</h5>
                                <h5>No. of Dependents: {{ $cibi->details['number_of_dependents'] ?? '' }}</h5>
                                <div class="mt-2 flex gap-4 px-8">
                                    <h5>Elementary: {{ $cibi->details['elementary'] ?? '' }}</h5>
                                    <h5>High School: {{ $cibi->details['high_school'] ?? '' }}</h5>
                                    <h5>College: {{ $cibi->details['college'] ?? '' }}</h5>
                                </div>
                                <h5>Contact No.: {{ $this->loan_application_member->contact }}</h5>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold">II. Loan Information</h4>
                            <div class="px-8">
                                <h5>1. Purpose of Loan: {{ $this->loan_application->purpose }}</h5>
                                <h5>2. Amount Applied: {{ renumber_format($this->loan_application->desired_amount, 2) }}</h5>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold">III. Financial Capability</h4>
                            <div class="px-8">
                                <h5>1. Source of Income:</h5>
                                <div class="px-8">
                                    <h6>Main: {{ $cibi->details['financial_capability']['main_income_source'] ?? '' }}</h6>
                                    <h6>Others: </h6>
                                    <ul class="px-4">
                                        @foreach ($cibi->details['financial_capability']['other_income_sources'] ?? [] as $other_income_source)
                                            <li>{{ $other_income_source }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <h5>Total Income: {{ $cibi->details['financial_capability']['total_income'] ?? '' }}</h5>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold">IV. Project Information</h4>
                            <div class="px-8">
                                <h5>1. Name of Project: {{ $cibi->details['project_information']['name'] ?? '' }}</h5>
                                <h5>2. Date Started: {{ $cibi->details['project_information']['date_started'] ?? '' }}</h5>
                                <h5>3. Commodities: {{ $cibi->details['project_information']['commodities'] ?? '' }}</h5>
                                <h5>4. Starting Capital: {{ $cibi->details['project_information']['starting_capital'] ?? '' }}</h5>
                                <h5>5. Present Capital: {{ $cibi->details['project_information']['present_capital'] ?? '' }}</h5>
                                <h5>6. Business Income (monthly): {{ $cibi->details['project_information']['monthly_business_income'] ?? '' }}</h5>
                                <h5>7. Business Expenses (pls. specify):</h5>
                                <ul class="px-8">
                                    @foreach ($cibi->details['project_information']['business_expenses'] ?? [] as $business_expense)
                                        <li>{{ $business_expense }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div>
                        <h4 class="font-bold">V. Assets</h4>
                        <div class="px-8">
                            <table class="my-4 doc-table text-xs">
                                <thead>
                                    <th class="doc-table-header-cell">Name</th>
                                    <th class="doc-table-header-cell">Value</th>
                                    <th class="doc-table-header-cell">Status</th>
                                </thead>
                                <tbody>
                                    @foreach ($cibi->details['assets'] ?? [] as $asset)
                                        <tr>
                                            <td class="doc-table-cell-center">{{ $asset['name'] ?? '' }}</td>
                                            <td class="doc-table-cell-center">{{ $asset['value'] ?? '' }}</td>
                                            <td class="doc-table-cell-center">{{ $asset['status'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold">VI. Existing Structure</h4>
                        <div class="px-8">
                            <table class="my-4 doc-table text-xs print:text-[8pt]">
                                <thead>
                                    <th class="doc-table-header-cell">Name</th>
                                    <th class="doc-table-header-cell">Rate</th>
                                </thead>
                                <tbody>
                                    @foreach ($cibi->details['existing_structure'] ?? [] as $existing_structure)
                                        <tr>
                                            <td class="doc-table-cell-center">{{ $existing_structure['name'] ?? '' }}</td>
                                            <td class="doc-table-cell-center">{{ $existing_structure['rate'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4 px-16 text-center">
                        <p>I hereby certify to the correctness and truthfulness of the above information. Any misrepresentation contained hereon will be enough ground for the disapproval of my application.</p>
                    </div>
                    <div class="mt-4 flex justify-evenly">
                        <div class="flex flex-col items-center">
                            <h3 class="min-w-[16rem] border-b border-black text-center">{{ $this->loan_application_member->full_name }}</h3>
                            <h4>Applicant's Name and Signature</h4>
                        </div>
                        <div class="flex flex-col items-center">
                            <h3 class="min-w-[16rem] border-b border-black text-center">{{ $this->loan_application->transaction_date?->format('m/d/Y') ?? today()->format('m/d/Y') }}</h3>
                            <h4>Date</h4>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h4>Collateral Presented: {{ $cibi->details['collateral'] ?? '' }}</h4>
                        <h4>Remarks:</h4>
                        <p></p>
                    </div>
                    <div class="mt-4">
                        <h4>______ Approved</h4>
                        <h4 class="pl-16">Amount Approved: {{ renumber_format($this->loan_application->desired_amount, 2) }}</h4>
                        <h4>______ Disapproved</h4>
                    </div>
                    <div class="mt-4">
                        <h4>Conducted by:</h4>
                        <div class="mt-4 flex justify-evenly">
                            <div class="flex flex-col items-center">
                                <h3 class="min-w-[16rem] border-b border-black"></h3>
                                <h4>Credit Assessment Officer</h4>
                            </div>
                            <div class="flex flex-col items-center">
                                <h3 class="min-w-[16rem] border-b border-black"></h3>
                                <h4>Date</h4>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div>
                            <h4>Recommended By:</h4>
                            <div class="mt-4 flex">
                                <div class="flex w-3/5 flex-col items-center">
                                    <h3 class="min-w-[16rem] border-b border-black"></h3>
                                    <h4>Manager</h4>
                                </div>
                                <div class="flex gap-2">
                                    <h3>Remarks:</h3>
                                    <p class="mt-4"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4>Approved by:</h4>
                            <div class="mt-4 grid grid-cols-2 gap-y-8">
                                <div class="col-span-2 flex flex-col items-center">
                                    <h3 class="min-w-[16rem] border-b border-black"></h3>
                                    <h4>Credit Committee Chairman</h4>
                                </div>
                                <div class="flex flex-col items-center">
                                    <h3 class="min-w-[16rem] border-b border-black"></h3>
                                    <h4>Credit Committee Secretary</h4>
                                </div>
                                <div class="flex flex-col items-center">
                                    <h3 class="min-w-[16rem] border-b border-black"></h3>
                                    <h4>Credit Committee Member</h4>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4>Remarks:</h4>
                            <p class="mt-2"></p>
                            <h5>Date: {{ $this->loan_application->transaction_date?->format('m/d/Y') ?? today()->format('m/d/Y') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </x-app.cashier.reports.report-layout>
    @else
        <x-app.cashier.reports.report-layout :signatories="$this->getSignatories()">
            <div class="print:text-[8pt]">
                <div class="text-center font-bold uppercase">
                    <p class="text-right text-sm font-normal">Confidential CIR 1</p>
                    <h3>CREDIT AND BACKGROUND INVESTIGATION REPORT</h3>
                </div>
                <table class="doc-table print:text-[8pt]">
                    <tr>
                        <th class="doc-table-header-cell">Borrower</th>
                        <td class="doc-table-cell">{{ $cibi->loan_application->member->full_name }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-header-cell">Address</th>
                        <td class="doc-table-cell">{{ $cibi->loan_application->member->address }}</td>
                    </tr>
                </table>
                <table class="doc-table print:text-[8pt]">
                    <tr>
                        <th class="doc-table-header-cell" colspan="4">A. PERSONAL DATA</th>
                    </tr>
                    <tr>
                        <th class="doc-table-header-cell">1. Basic Information</th>
                        <th class="doc-table-header-cell">Borrower</th>
                        <th class="doc-table-header-cell">Spouse</th>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Name</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->full_name }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['name'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Nickname/Alias</th>
                        <td class="doc-table-cell">{{ $cibi->details['borrower']['nickname'] ?? '' }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['nickname'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Middle Name</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->middle_name }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['middle_name'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Date of Birth</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->dob?->format('m/d/Y') }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['date_of_birth'] ?? null ? date_create($cibi->details['spouse']['date_of_birth'])->format('m/d/Y') : '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Age</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->age }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['age'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Contact No.</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->contact }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['contact_number'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Civil Status</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->civil_status?->name }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['civil_status'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Nationality</th>
                        <td class="doc-table-cell">{{ $cibi->details['borrower']['nationality'] ?? '' }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['nationality'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Address</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->address }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['address'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">Highest Educational Attainment</th>
                        <td class="doc-table-cell">{{ $this->loan_application_member->highest_educational_attainment }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['highest_educational_attainment'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">School</th>
                        <td class="doc-table-cell">{{ $cibi->details['borrower']['school'] ?? '' }}</td>
                        <td class="doc-table-cell">{{ $cibi->details['spouse']['school'] ?? '' }}</td>
                    </tr>
                </table>
                <table class="doc-table print:text-[8pt]">
                    <tr>
                        <th class="doc-table-header-cell">2. Name of Children</th>
                        <th class="doc-table-header-cell">Birthdate</th>
                        <th class="doc-table-header-cell">Age</th>
                        <th class="doc-table-header-cell">Course/School</th>
                    </tr>
                    @forelse ($cibi->details['children'] ?? [] as $child)
                        <tr>
                            <th class="doc-table-cell">{{ $child['name'] ?? '' }}</th>
                            <th class="doc-table-cell">{{ $child['birthdate'] ?? null ? date_create($child['birthdate'])->format('m/d/Y') : null }}</th>
                            <td class="doc-table-cell">{{ $child['birthdate'] ?? null ? today()->diffInYears($child['birthdate']) : null }}</td>
                            <td class="doc-table-cell">{{ $child['course_and_school'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="doc-table-cell" colspan="4">No children found.</td>
                        </tr>
                    @endforelse
                </table>
                <table class="doc-table print:text-[8pt]">
                    <tr>
                        <th class="doc-table-header-cell">3. Assets</th>
                        <th class="doc-table-header-cell">Value</th>
                        <th class="doc-table-header-cell">Status</th>
                    </tr>
                    @forelse ($cibi->details['assets'] ?? [] as $asset)
                        <tr>
                            <th class="doc-table-cell">{{ $asset['name'] ?? '' }}</th>
                            <td class="doc-table-cell">{{ $asset['value'] ?? '' }}</td>
                            <td class="doc-table-cell">{{ $asset['status'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="doc-table-cell" colspan="3">No assets found.</td>
                        </tr>
                    @endforelse
                </table>
                <table class="doc-table print:text-[8pt]">
                    <tr>
                        <th class="doc-table-header-cell" colspan="4">4. Employment Verification</th>
                    </tr>
                    <tr>
                        <th class="doc-table-header-cell">Particulars</th>
                        <th class="doc-table-header-cell">Borrower</th>
                        <th class="doc-table-header-cell">Co-Borrower 1</th>
                        <th class="doc-table-header-cell">Co-Borrower 2</th>
                    </tr>
                    @foreach ($cibi->details['employment_verification'] ?? [] as $key => $verification_item)
                        <tr>
                            <th class="doc-table-cell">{{ $verification_item['particulars'] }}</th>
                            <td class="doc-table-cell">{{ $verification_item['borrower'] ?? '' }}</td>
                            <td class="doc-table-cell">{{ $verification_item['coborrower_1'] ?? '' }}</td>
                            <td class="doc-table-cell">{{ $verification_item['coborrower_2'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </table>
                <table class="doc-table">
                    <tr>
                        <th class="doc-table-header-cell" colspan="4">5. Income Verification</th>
                    </tr>
                    <tr>
                        <th class="doc-table-header-cell">Particulars</th>
                        <th class="doc-table-header-cell">Borrower</th>
                        <th class="doc-table-header-cell">Co-Borrower 1</th>
                        <th class="doc-table-header-cell">Co-Borrower 2</th>
                    </tr>
                    @foreach ($cibi->details['income_verification'] ?? [] as $key => $verification_item)
                        <tr>
                            <th class="doc-table-cell">{{ $verification_item['particulars'] }}</th>
                            <td class="doc-table-cell">{{ $verification_item['borrower'] ?? '' }}</td>
                            <td class="doc-table-cell">{{ $verification_item['coborrower_1'] ?? '' }}</td>
                            <td class="doc-table-cell">{{ $verification_item['coborrower_2'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </table>
                <table class="doc-table print:text-[8pt]">
                    <tr>
                        <th class="doc-table-header-cell" colspan="6">6. Nature of Loan</th>
                    </tr>
                    <tr>
                        <th class="doc-table-header-cell">Type of Loan</th>
                        <th class="doc-table-header-cell">Date Approved</th>
                        <th class="doc-table-header-cell">Amount Granted</th>
                        <th class="doc-table-header-cell">Mode of Payment</th>
                        <th class="doc-table-header-cell">Credit Terms</th>
                        <th class="doc-table-header-cell">Outstanding Balance</th>
                    </tr>
                    <tr>
                        <th class="doc-table-cell">{{ $cibi->loan_application->loan_type->name }}</th>
                        <td class="doc-table-cell">{{ $cibi->loan_application->approval_date?->format('m/d/Y') }}</td>
                        <td class="doc-table-cell">{{ renumber_format($cibi->loan_application->desired_amount, 2) }}</td>
                        <td class="doc-table-cell"></td>
                        <td class="doc-table-cell">{{ $cibi->loan_application->number_of_terms }}</td>
                        <td class="doc-table-cell">{{ renumber_format($cibi->loan_application->desired_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="doc-table-cell" colspan="3">Date request received: {{ $cibi->loan_application->transaction_date->format('m/d/Y') }}</td>
                        <td class="doc-table-cell" colspan="3">Date of Report: {{ config('app.transaction_date')?->format('m/d/Y') }}</td>
                    </tr>
                </table>
            </div>
        </x-app.cashier.reports.report-layout>
    @endif
</x-filament-panels::page>
