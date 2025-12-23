<div x-data>
    <div class="text-[8pt]" x-ref="print">
        <div class="mb-8 flex flex-col items-center justify-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-24 print:h-16">
            <h2 class="text-center text-sm font-bold">SULTAN KUDARAT STATE UNIVERSITY - MULTI-PURPOSE COOPERATIVE
                (SKSU-MPC)</h2>
        </div>
        <div class="border border-black">
            <p class="border border-black p-1">This Deposit / payment is subject to the Terms and Conditions covering
                this account</p>
            <div class="border border-black p-1">
                <strong>TELLER'S VALIDATION</strong>
                <p>(THIS IS YOUR RECEIPT WHEN SYSTEM VALIDATED)</p>
            </div>
            <div class="border border-black p-1">
                <p><strong>Reference #:</strong> {{ $reference_number }}</p>
                <p><strong>Name:</strong> {{ $name }}</p>
                <p><strong>OR Number:</strong> {{ $or_number }}</p>
                <p><strong>OR Date:</strong> {{ $or_date }}</p>
                <p><strong>Billable Date:</strong> {{ $billable_date }}</p>
                <p><strong>Transaction Date:</strong> {{ $date }}</p>
            </div>
            <div class="border border-black p-1">
                <p><strong>TOTAL AMOUNT DUE:</strong> {{ $total_amount_due }}</p>
                <p><strong>TOTAL AMOUNT PAID:</strong> {{ $total_amount_paid }}</p>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <x-filament::button icon="heroicon-o-printer" class="w-full"
                            @click="printOut($refs.print.outerHTML, 'Transaction Receipt')">Print
        </x-filament::button>
    </div>
</div>
