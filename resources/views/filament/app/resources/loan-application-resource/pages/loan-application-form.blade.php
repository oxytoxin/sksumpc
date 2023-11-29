@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>
    <div x-data class="mx-auto">
        <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
            <x-app.cashier.reports.report-heading />
            <h4 class="text-3xl print:text-[12pt] text-center mt-4 font-bold">APPLICATION FORM</h4>
            <div class="my-2 grid grid-cols-2 print:text-[10pt]">
                <div class="space-x-4 flex">
                    <h4 class="font-bold">NAME:</h4>
                    <h4>{{ $loan_application->member->full_name }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">PRIORITY NUMBER:</h4>
                    <h4>{{ $loan_application->priority_number }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">ADDRESS:</h4>
                    <h4>{{ $loan_application->member->address }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">BIRTHDATE:</h4>
                    <h4>{{ $loan_application->member->dob?->format('F d, Y') }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">CONTACT NUMBER:</h4>
                    <h4></h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">DESIRED LOAN AMOUNT:</h4>
                    <h4>{{ format_money($loan_application->desired_amount, 'PHP') }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">PURPOSE:</h4>
                    <h4>{{ $loan_application->purpose }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">MONTHLY AMORTIZATION:</h4>
                    <h4>{{ format_money($loan_application->monthly_payment, 'PHP') }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">TYPE OF LOAN:</h4>
                    <h4>{{ $loan_application->loan_type->name }}</h4>
                </div>
                <div class="space-x-4 flex">
                    <h4 class="font-bold">TERMS APPLIED:</h4>
                    <h4>{{ $loan_application->number_of_terms }}</h4>
                </div>
            </div>
            <div class="flex flex-col items-center mt-8 print:leading-[0]">
                <h4 class="uppercase font-bold text-xl print:text-[10pt]">{{ $loan_application->member->full_name }}</h4>
                <h4 class="print:text-[8pt]">(Signature over printed name of applicant)</h4>
            </div>
            <div class="mt-4 print:text-[10pt]">
                <h3 class="font-bold">CREDIT COMMITTEE ACTION</h3>
                <div class="my-2 flex">
                    <div class="w-2/5">
                        <div class="space-x-4 flex">
                            <h4>Returned due to:</h4>
                            <h4></h4>
                        </div>
                        <div class="space-x-4 flex">
                            <h4>Disapproved due to:</h4>
                            @if ($loan_application->disapproval_reason->id == 1)
                                <h4>{{ $loan_application->remarks }}</h4>
                            @else
                                <h4>{{ $loan_application->disapproval_reason?->name }}</h4>
                            @endif
                        </div>
                    </div>
                    <div class="space-x-4 flex">
                        <h4>Recommending approval priority number:</h4>
                        <h4></h4>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex flex-wrap justify-around gap-y-8">
                        @foreach ($signatories as $approver)
                            <div class="flex w-1/3 flex-col items-center mt-8">
                                <p class="font-bold uppercase">{{ $approver['name'] }}</p>
                                <p>{{ $approver['position'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr class="border-2 print:hidden border-black my-4">
            <div class="print:break-before-page">
                <div class="grid grid-cols-2">
                    <h4>Name: {{ $loan_application->member->full_name }}</h4>
                    <h4>Date: <span class="uppercase">{{ today()->format('F d, Y') }}</span></h4>
                    <h4>Station: {{ $loan_application->member->division?->name }}</h4>
                </div>
                <h3 class="font-bold text-center my-4">STATEMENT OF ACCOUNT</h3>
                <div>
                    <table class="w-full print:text-[9pt]">
                        <thead>
                            <tr>
                                <th class="border border-black px-2">Date</th>
                                <th class="border border-black px-2">Capital Share</th>
                                <th class="border border-black px-2">Particulars</th>
                                <th class="border border-black px-2">Date Granted</th>
                                <th class="border border-black px-2">Amount Granted</th>
                                <th class="border border-black px-2">Monthly Amortization</th>
                                <th class="border border-black px-2">Balance</th>
                                <th class="border border-black px-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loan_application->member->loans()->with('loan_type')->get() as $loan)
                                <tr>
                                    <td class="border border-black px-2">{{ $loan->transaction_date->format('m/d/Y') }}</td>
                                    <td class="border border-black px-2">{{ format_money(collect($loan->deductions)->firstWhere('code', 'cbu_common')['amount'], 'PHP') }}</td>
                                    <td class="border border-black px-2">{{ $loan->loan_type->name }}</td>
                                    <td class="border border-black px-2">{{ $loan->release_date?->format('m/d/Y') }}</td>
                                    <td class="border border-black px-2">{{ format_money($loan->gross_amount, 'PHP') }}</td>
                                    <td class="border border-black px-2">{{ format_money($loan->monthly_payment, 'PHP') }}</td>
                                    <td class="border border-black px-2">{{ format_money($loan->outstanding_balance, 'PHP') }}</td>
                                    <td class="border border-black px-2">{{ $loan->posted ? 'Approved' : 'On Process' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border border-black px-2 text-center">No loans found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-16">
                    <p>Certified Correct:</p>
                    <div class="flex justify-around">
                        <div class="flex flex-col items-center mt-8">
                            <p class="font-bold uppercase">{{ $loan_application->processor->name }}</p>
                            <p>Loan Officer</p>
                        </div>
                        <div class="flex flex-col items-center mt-8">
                            <p>{{ today()->format('m/d/Y') }}</p>
                            <p>Date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 flex justify-end space-x-2">
            <x-filament::button outlined tag="a" href="{{ route('filament.app.resources.loan-applications.index') }}">Back to Loan Applications</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Loan Application Form')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
