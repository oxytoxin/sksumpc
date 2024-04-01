<div x-data>
    <div x-ref="print">
        <div class="mb-8 flex flex-col items-center justify-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-24 print:h-16">
            <h2 class="text-center font-bold text-lg">SULTAN KUDARAT STATE UNIVERSITY - MULTI-PURPOSE COOPERATIVE
                (SKSU-MPC)
            </h2>
        </div>
        <div class="border border-black">
            <p class="border border-black p-2">This Deposit / payment is subject to the Terms and Conditions covering
                this
                account</p>
            <h3 class="border border-black p-2">&nbsp;</h3>
            <div class="border border-black p-2">
                <strong>TELLER'S VALIDATION</strong>
                <p>(THIS IS YOUR RECEIPT WHEN SYSTEM VALIDATED)</p>
            </div>
            @foreach ($transactions as $transaction)
                <div class="border border-black p-2">
                    <p><strong>Account Number:</strong> {{ $transaction['account_number'] }}</p>
                    <p><strong>Account Name:</strong> {{ $transaction['account_name'] }}</p>
                    <div class="flex gap-4">
                        <strong>{{ $transaction['payment_type'] }}</strong>
                        <p>{{ renumber_format($transaction['amount'], 2) }}</p>
                        <p>{{ now()->format('m/d/Y H:i:s') }}</p>
                    </div>
                    <p>REFERENCE #: {{ $transaction['reference_number'] }}</p>
                    <p>{{ $transaction['remarks'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-4">
        <x-filament::button icon="heroicon-o-printer" class="w-full"
            @click="printOut($refs.print.outerHTML, 'Transaction Receipt')">Print</x-filament::button>
    </div>
</div>
