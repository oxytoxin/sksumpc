@php
    use function Filament\Support\format_money;
@endphp
<div x-data class="max-w-4xl mx-auto">
    <div class="p-4" x-ref="print">
        <div class="flex justify-center mb-16">
            <div class="flex space-x-24 items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-32">
                <div class="flex flex-col items-center">
                    <p>Sultan Kudarat State University</p>
                    <p>MULTI-PURPOSER COOPERATIVE</p>
                    <p>Bo. 2, EJC Montilla, Tacurong City</p>
                    <p>CDA Reg. No.: 9520-12000926</p>
                    <div class="flex space-x-12">
                        <p>CIN: 0103120093</p>
                        <p>TIN: 005-811-330</p>
                    </div>
                    <p>Contact No: 09557966507/email address: sksu.mpc@gmail.com</p>
                    <h4 class="text-3xl mt-8 font-bold">DISCLOSURE SHEET</h4>
                </div>
            </div>
        </div>
        <div class="my-4 flex">
            <div class="w-2/3">
                <h4>NAME: <strong>{{ $loan->member->full_name }}</strong></h4>
                <h4>LOAN TYPE: <strong>{{ $loan->loan_type->name }}</strong></h4>
                <p>Priority Number:</p>
            </div>
            <div class="w-1/3 font-bold flex justify-between">
                <p>DATE:</p>
                <p>{{ $loan->transaction_date->format('F d, Y') }}</p>
            </div>
        </div>
        <div class="flex justify-between">
            <p>AMOUNT GRANTED</p>
            <p class="font-bold">{{ format_money($loan->gross_amount, 'PHP') }}</p>
        </div>
        <div class="px-16">
            <p>LESS:</p>
            <div class="px-16">
                @foreach ($loan->deductions as $deduction)
                    <div class="flex justify-between">
                        <p>{{ $deduction['name'] }}</p>
                        <p class="font-bold">{{ format_money($deduction['amount'], 'PHP') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex pl-32 justify-between">
            <p class="font-bold">TOTAL DEDUCTIONS</p>
            <p class="font-bold">{{ format_money($loan->deductions_amount, 'PHP') }}</p>
        </div>
        <div class="flex justify-between">
            <p class="font-bold">NET PROCEEDS</p>
            <p class="font-bold">{{ format_money($loan->net_amount, 'PHP') }}</p>
        </div>

        <div class="mt-16 grid grid-cols-2 gap-4">
            <div>
                <p>Prepared by:</p>
                <div class="flex flex-col items-center mt-8">
                    <p>ELLEN D. ANDRADA</p>
                    <p>Loan Officer</p>
                </div>
            </div>
            <div>
                <p>Approved by:</p>
                <div class="flex flex-col items-center mt-8">
                    <p>FLORA C. DAMANDAMAN</p>
                    <p>Manager</p>
                </div>
            </div>
            <div>
                <p>Checked by:</p>
                <div class="flex flex-col items-center mt-8">
                    <p>ELLEN D. ANDRADA</p>
                    <p>Bookkeeper</p>
                </div>
            </div>
            <div>
                <p>Received by:</p>
                <div class="flex flex-col items-center mt-8">
                    <p>{{ $loan->member->full_name }}</p>
                    <p>Borrower</p>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
