@php
    use function Filament\Support\format_money;
    $loans = $loan_application->member->loans()->with('loan_type')->get();
    $loan_applications = $loan_application->member->loan_applications()->doesntHave('loan')->with('loan_type')->get();
    $loan_application->load('comakers');
    $treasurer = App\Models\User::whereRelation('roles', 'name', 'treasurer')->first();
    $witness1 = new App\Oxytoxin\DTO\Loan\LoanApproval($treasurer->name, 'Treasurer');

    if ($loan_application->desired_amount > 50000) {
        $bod_chairperson = App\Models\User::whereRelation('roles', 'name', 'bod-chairperson')->first();
        $witness2 = new App\Oxytoxin\DTO\Loan\LoanApproval($bod_chairperson->name, 'BOD-Chairperson');
    } else {
        $manager = App\Models\User::whereRelation('roles', 'name', 'manager')->first();
        $witness2 = new App\Oxytoxin\DTO\Loan\LoanApproval($manager->name, 'Manager');
    }
@endphp

<x-filament-panels::page>
    <div x-data class="mx-auto">
        <div class="p-4 print:w-full print:text-[8pt] print:leading-tight" x-ref="print">
            <div>
                <x-app.cashier.reports.report-heading/>
                <h4 class="text-center text-xl font-bold print:text-[10pt]">APPLICATION FORM</h4>
                <div class="my-2 grid grid-cols-2 print:text-[8pt]">
                    <div class="flex space-x-4">
                        <h4 class="font-bold">NAME:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4"> {{ $loan_application->member->full_name }} </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">PRIORITY NUMBER:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ $loan_application->priority_number }} </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">ADDRESS:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ $loan_application->member->address }} </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">BIRTHDATE:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4"> {{ $loan_application->member->dob?->format('F d, Y') }}</h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">CONTACT NUMBER:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ $loan_application->member->contact }} </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">DESIRED LOAN AMOUNT:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4"> {{ format_money($loan_application->desired_amount, 'PHP') }}</h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">PURPOSE:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ $loan_application->purpose }}</h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">MONTHLY AMORTIZATION:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">
                            @if ($loan_application->loan_type->has_monthly_amortization)
                                {{ format_money($loan_application->monthly_payment, 'PHP') }}
                            @endif
                        </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">TYPE OF LOAN:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ $loan_application->loan_type->name }} </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">TERMS APPLIED:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ $loan_application->number_of_terms }} </h4>
                    </div>
                    <div class="flex space-x-4">
                        <h4 class="font-bold">CAPITAL SHARE:</h4>
                        <h4 class="min-w-[8rem] border-b border-black px-4">{{ renumber_format($loan_application->cbu_amount) }} </h4>
                    </div>
                </div>
                <div class="mt-8 flex flex-col items-center print:leading-[0]">
                    <h4 class="text-xl font-bold uppercase print:text-[8pt]">{{ $loan_application->member->full_name }} </h4>
                    <h4 class="print:text-[7pt]">(Signature over Printed Name of applicant)</h4>
                </div>
                <div class="mt-2 print:text-[8pt]">
                    <h3 class="font-bold">CREDIT COMMITTEE ACTION</h3>
                    <div class="my-2 flex items-start">
                        <div class="w-2/5">
                            <div class="flex space-x-4">
                                <h4>Returned due to:</h4>
                                <h4 class="inline-block min-w-[8rem] border-b border-black px-4"></h4>
                            </div>
                            <div class="flex space-x-4">
                                <h4>Disapproved due to:</h4>
                                <h4 class="inline-block min-w-[8rem] border-b border-black px-4">
                                    @if ($loan_application->disapproval_reason?->id == 1)
                                        {{ $loan_application->remarks }}
                                    @else
                                        {{ $loan_application->disapproval_reason?->name }}
                                    @endif
                                </h4>

                            </div>
                        </div>
                        <div class="flex space-x-4">
                            <h4>Recommending approval priority number:</h4>
                            <h4 class="inline-block min-w-[8rem] border-b border-black px-4"></h4>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex flex-wrap justify-around gap-y-4">
                            @foreach ($signatories as $approver)
                                <div class="mt-4 flex w-1/3 flex-col items-center">
                                    <p class="font-bold">{{ $approver['name'] }}</p>
                                    <p class="print:text-[7pt]">{{ $approver['position'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr class="my-8 border-2 border-black">
                <div class="mt-4 print:text-[8pt]">
                    <div class="grid grid-cols-2">
                        <h4>Name:
                            <span class="min-w-[8rem] border-b border-black px-4">
                                {{ $loan_application->member->full_name }}
                            </span>
                        </h4>
                        <h4>
                            Date: <span class="min-w-[8rem] border-b border-black px-4">{{ config('app.transaction_date')?->format('F d, Y') }}</span>
                        </h4>
                        <h4>
                            Station: <span class="inline-block min-w-[8rem] border-b border-black px-4">{{ $loan_application->member->division?->name }}</span>
                        </h4>
                    </div>
                    <h3 class="my-4 text-center font-bold">STATEMENT OF ACCOUNT</h3>
                    <div>
                        <table class="w-full print:text-[9pt]">
                            <thead>
                            <tr>
                                <th class="border border-black px-2">Date</th>
                                <th class="border border-black px-2">Particulars</th>
                                <th class="border border-black px-2">Date Granted</th>
                                <th class="border border-black px-2">Amount Granted</th>
                                <th class="border border-black px-2">Monthly Amortization</th>
                                <th class="border border-black px-2">Balance</th>
                                <th class="border border-black px-2">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($loans as $loan)
                                <tr>
                                    <td class="border border-black px-2">{{ $loan->transaction_date->format('m/d/Y') }}</td>
                                    <td class="border border-black px-2">{{ $loan->loan_type->name }}</td>
                                    <td class="border border-black px-2">{{ $loan->release_date?->format('m/d/Y') }}</td>
                                    <td class="border border-black px-2">{{ format_money($loan->gross_amount, 'PHP') }}</td>
                                    <td class="border border-black px-2">{{ format_money($loan->monthly_payment, 'PHP') }} </td>
                                    <td class="border border-black px-2">
                                        {{ format_money($loan->outstanding_balance, 'PHP') }}
                                    </td>
                                    <td class="border border-black px-2">
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($loan_applications as $la)
                                <tr>
                                    <td class="border border-black px-2">{{ $la->transaction_date->format('m/d/Y') }}</td>
                                    <td class="border border-black px-2">{{ $la->loan_type->name }}</td>
                                    <td class="border border-black px-2"></td>
                                    <td class="border border-black px-2">{{ format_money($la->desired_amount, 'PHP') }}</td>
                                    <td class="border border-black px-2">{{ format_money($la->monthly_payment, 'PHP') }}</td>
                                    <td class="border border-black px-2"></td>
                                    <td class="border border-black px-2">{{ $la->status_name }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8" class="border border-black px-2 text-center">Nothing follows.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-16">
                        <p>Certified Correct:</p>
                        <div class="flex justify-around">
                            <div class="mt-8 flex flex-col items-center">
                                <p class="font-bold uppercase">{{ $loan_application->processor?->name }}</p>
                                <p>Loan Officer</p>
                            </div>
                            <div class="mt-8 flex flex-col items-center">
                                <p>{{ config('app.transaction_date')?->format('m/d/Y') }}</p>
                                <p>Date</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="break-before-page">
                <div class="hidden print:block">
                    <x-app.cashier.reports.report-heading/>
                </div>
                <h1 class="my-8 text-center font-bold">PROMISSORY NOTE</h1>
                <div class="mb-4 flex">
                    <div class="flex-1">
                        <p>
                            P <span class="inline-block min-w-[8rem] border-b border-black px-4">{{ renumber_format($loan_application->desired_amount, 2) }}</span>
                        </p>
                    </div>
                    <div class="flex-1">
                        <p>Priority Number: <span class="inline-block min-w-[8rem] border-b border-black px-4">{{ $loan_application->priority_number }}</span></p>
                        <p>Authenticated by: <span class="inline-block min-w-[12rem] border-b border-black px-4">&nbsp;</span></p>
                    </div>
                </div>
                <div class="flex flex-col gap-y-1">
                    <p class="indent-8">
                        For the value received, I/We jointly and severally, promise to pay the SKSU - MPC or order the sum of <span class="border-b border-black px-4 uppercase">{{ $loan_application->desired_amount_in_words }} pesos only (P{{ renumber_format($loan_application->desired_amount, 2) }})</span>
                        payable in <span class="border-b border-black px-4">{{ $loan_application->number_of_terms }} months</span>
                        equal installment of <span class="inline-block min-w-[8rem] border-b border-black px-4">
                            @if ($loan_application->loan_type->has_monthly_amortization)
                                (P {{ renumber_format($loan_application->monthly_payment, 2) }})
                            @endif
                        </span> the first payment to
                        be made on <span class="inline-block min-w-[8rem] border-b border-black px-4 text-center indent-0">{{ $loan_application->payment_start_date?->format('F d, Y') }}</span>
                        and every payday thereafter until the full amount has been paid.
                    </p>
                    <p class="indent-8">Collateral: Monthly/Semi-monthly salary PAYROLL DEDUCTION</p>
                    <p class="indent-8">
                        In case of any default in payments as herein agreed, the entire balance of this note shall become immediately due and payable.
                    </p>
                    <p class="indent-8">
                        It is further agreed that in case payment shall not be made at maturity, I/We will pay the penalty of 1% per month of the principal balance and the interest due this note starting from
                        <span class="inline-block min-w-[8rem] border-b border-black px-4 indent-0">
                            {{ $loan_application->surcharge_start_date?->format('F d, Y') }}
                        </span>
                    </p>
                    <p class="indent-8">
                        In case of judicial action of this obligation or any part of it, the debtor waives all his rights
                        under the provision of Rule 3, Section 13 and Rule 39, Section 12 of the Rules of Court.
                    </p>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-x-32 gap-y-12">
                    <div class="flex flex-col justify-end">
                        <p class="border-b border-black text-center uppercase">
                            {{ $loan_application->member->address }}&nbsp;
                        </p>
                        <p class="text-center">Address</p>
                    </div>
                    <div class="flex flex-col justify-end">
                        <p class="border-b border-black text-center uppercase">
                            {{ $loan_application->member->full_name }}</p>
                        <p class="text-center">( Signature over Printed Name of Borrower)</p>
                    </div>
                    @foreach ($loan_application->comakers ?? [] as $comaker)
                        <div class="flex flex-col justify-end">
                            <p class="border-b border-black text-center uppercase">{{ $comaker->full_name }}</p>
                            <p class="text-center">( Signature over Printed Name of Co-Borrower)</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h1 class="my-4 text-center font-bold">DEED OF ASSIGNMENT</h1>
                <p>KNOWN ALL MEN BY THESE PRESENTS:</p>
                <div class="mt-4 flex flex-col gap-4">
                    <p class="indent-8">
                        That we
                        <strong>
                            {{ $loan_application->member->full_name }} (Borrower),
                            @foreach ($loan_application->comakers as $comaker)
                                {{ $comaker->full_name }} (Co-Borrower),
                            @endforeach
                        </strong>
                        @php
                            $addresses = [];
                            if ($loan_application->member->address) {
                                $addresses[] = $loan_application->member->address;
                            }

                            foreach ($loan_application->comakers as $comaker) {
                                if ($comaker->address) {
                                    $addresses[] = $comaker->address;
                                }
                            }
                        @endphp
                        of legal age single/married, Filipino and with postal addresses at <span class="inline-block min-w-[8rem] border-b border-black px-4 uppercase">{{ implode(', ', $addresses) }}</span> and in consideration of the sum
                        <span class="border-b border-black px-4 uppercase">
                            {{ $loan_application->desired_amount_in_words }} pesos only
                        </span>
                        <span class="border-b border-black px-4"> (P {{ renumber_format($loan_application->desired_amount, 2) }}) </span>
                        Granted to the borrower as salary loan by the SKSU MPC, EJC Montilla, Tacurong City
                        by these presents do hereby code and assign in favor of the SKSU MPC our salaries, bonuses,
                        allowances, gratuity/separation pay retirement benefits, monetary value of accumulated leave
                        credit and any other form monetary of pecuniary benefits from our employer, Teachers and
                        Employees of SKSU and with GSIS or any other entity or institute due to us.
                    </p>
                    <p class="indent-8">
                        We hereby authorized the Disbursement Officer or Collecting Officer of SKSU MPC to collect
                        withhold any amount equivalent to the outstanding obligation inclusive of interest due from
                        aforementioned monetary benefits and remit the same to SKSU MPC for our account or accounts
                        herein borrower:
                    </p>
                    <p class="indent-8">
                        In WITNESS WHEREOF, we have hereunto set our hands this
                        <strong>{{ $loan_application->transaction_date->format('jS \d\a\y \o\f\ F, Y') }}</strong> at
                        <span class="inline-block min-w-[8rem] border-b border-black px-4 indent-0">City of Tacurong</span>.
                    </p>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-x-32 gap-y-12">
                    <div>
                    </div>
                    <div>
                        <p class="border-b border-black text-center uppercase"> {{ $loan_application->member->full_name }}</p>
                        <p class="text-center">( Signature over Printed Name of Borrower)</p>
                    </div>
                    @foreach ($loan_application->comakers as $comaker)
                        <div>
                            <p class="border-b border-black text-center uppercase">{{ $comaker->full_name }}</p>
                            <p class="text-center">( Signature over Printed Name of Co-Borrower)</p>
                        </div>
                    @endforeach
                </div>
                <h3 class="mt-2 text-center font-bold">Signed in the presence of:</h3>
                <div class="mt-4 grid grid-cols-2 gap-x-32 gap-y-12">
                    <div>
                        <p class="border-b border-black text-center uppercase">{{ $witness1->name }}</p>
                        <p class="text-center">{{ $witness1->position }}</p>
                    </div>
                    <div>
                        <p class="border-b border-black text-center">{{ $witness2->name }}</p>
                        <p class="text-center">{{ $witness2->position }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-2 p-4">
            <x-filament::button outlined tag="a" href="{{ route('filament.app.resources.loan-applications.index') }}">Back to Loan Applications</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Loan Application Form')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
