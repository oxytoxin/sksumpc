@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>
    <div x-data class="max-w-6xl mx-auto">
        <div class="p-4" x-ref="print">
            <div class="flex justify-center mb-16">
                <div class="flex space-x-24 items-center">
                    <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-32">
                    <div class="flex flex-col items-center">
                        <strong>SULTAN KUDARAT STATE UNIVERSITY - MULTI-PURPOSE COOPERATIVE</strong>
                        <strong>(SKSU-MPC)</strong>
                        <p>Bo. 2, EJC Montilla, Tacurong City</p>
                        <p>CDA Reg. No.: 9520-12000926 / CIN: 0103120093 / TIN: 005-811-330</p>
                        <p>Contact No: 0906-826-1905 or 0966-702-9200</p>
                        <p>Email Address: sksu.mpc@gmail.com</p>
                    </div>
                </div>
            </div>
            <h4 class="text-3xl text-center mt-8 font-bold">APPLICATION FORM</h4>
            <div class="my-4 grid grid-cols-2">
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
                    <h4>{{ $loan_application->member->dob->format('F d, Y') }}</h4>
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
            <div class="flex flex-col items-center mt-16">
                <h4 class="uppercase font-bold text-xl">{{ $loan_application->member->full_name }}</h4>
                <h4>(Signature over printed name of applicant)</h4>
            </div>
            <div class="mt-8">
                <h3 class="font-bold">CREDIT COMMITTEE ACTION</h3>
                <div class="my-4 grid grid-cols-2">
                    <div class="space-x-4 flex">
                        <h4 class="font-bold">RETURNED DUE TO:</h4>
                        <h4></h4>
                    </div>
                    <div class="space-x-4 flex">
                        <h4 class="font-bold">RECOMMENDING APPROVAL PRIORITY NUMBER:</h4>
                        <h4></h4>
                    </div>
                    <div class="space-x-4 flex">
                        <h4 class="font-bold">DISAPPROVED DUE TO:</h4>
                        <h4></h4>
                    </div>
                </div>
                <div class="mt-12">
                    <div class="grid grid-cols-2 gap-y-8">
                        @foreach ($approvers as $approver)
                            <div class="flex flex-col items-center mt-8">
                                <p class="font-bold uppercase">{{ $approver['name'] }}</p>
                                <p>{{ $approver['position'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr class="border-2 print:hidden border-black my-8">
            <div class="print:break-before-page">
                <div class="grid grid-cols-2">
                    <h4>Name: {{ $loan_application->member->full_name }}</h4>
                    <h4>Date: <span class="uppercase">{{ today()->format('F d, Y') }}</span></h4>
                    <h4>Station: {{ $loan_application->member->division?->name }}</h4>
                </div>
                <h3 class="font-bold text-center my-8">STATEMENT OF ACCOUNT</h3>
                <div>
                    <table class="w-full">
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
                                    <td class="border border-black px-2">{{ $loan->release_date->format('m/d/Y') }}</td>
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
        <div class="p-4 flex justify-end">
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Loan Application Form')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
