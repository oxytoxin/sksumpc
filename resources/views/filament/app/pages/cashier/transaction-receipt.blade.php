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
            @foreach ($transactions as $transaction)
                <div class="border border-black p-1">
                    <p><strong>Payee:</strong> {{ $transaction['payee'] }}</p>
                    <p><strong>Account Number:</strong> {{ $transaction['account_number'] }}</p>
                    <p><strong>Account Name:</strong> {{ $transaction['account_name'] }}</p>
                    <div class="flex gap-4">
                        <strong>{{ $transaction['payment_type'] }}</strong>
                        <p>{{ renumber_format($transaction['amount'], 2) }}</p>
                    </div>
                    <p>REFERENCE #: {{ $transaction['reference_number'] }}</p>
                    <p>TRANSACTION
                        DATE: {{ date_create($transaction['transaction_date'] ?? $this->transaction_date)->format('m/d/Y') }}</p>
                    <p>{{ $transaction['remarks'] }}</p>
                </div>
            @endforeach
            <div class="border border-black p-1">
                <p><strong>TOTAL
                        AMOUNT:</strong> {{ \Filament\Support\format_money(collect($transactions)->sum('amount'), 'PHP') }}
                </p>
            </div>
        </div>
    </div>

    @if(count($mso_links) > 0)
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($mso_links as $link)
                <x-filament::button
                        :href="$link['url']"
                        tag="a"
                        icon="heroicon-o-arrow-top-right-on-square"
                        target="_blank"
                        color="info"
                        size="sm"
                >
                    View {{ $link['type'] }} SL
                </x-filament::button>
            @endforeach
        </div>
    @endif
    <div class="mt-4">
        <x-filament::button icon="heroicon-o-printer" class="w-full"
                            @click="printOut($refs.print.outerHTML, 'Transaction Receipt')">Print
        </x-filament::button>
    </div>
</div>
