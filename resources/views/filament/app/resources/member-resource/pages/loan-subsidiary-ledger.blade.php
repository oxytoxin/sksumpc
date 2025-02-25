@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div class="print:text-[10pt]" x-ref="print">
        <div class="text-center">
            <h1 class="font-bold">SKSU-MULTI-PURPOSE COOPERATIVE</h1>
            <h2>Access Campus, EJC Montilla, Tacurong City</h2>
        </div>
        <h2 class="my-4 text-center font-bold">SUBSIDIARY LEDGER FOR LOAN</h2>
        <div class="grid grid-cols-2">
            <div class="leading-4">
                <p>Account Number: <strong>{{ $loan->loan_account->number }}</strong></p>
                <p>Name: <strong>{{ $loan->member->alt_full_name }}</strong></p>
                <p>Date Granted: {{ $loan->release_date?->format('F d, Y') }}</p>
                <p>Due Date: {{ $loan->maturity_date?->format('F d, Y') }}</p>
                <p>Amount Granted: {{ number_format($loan->gross_amount, 2) }}</p>
                <p>Purpose: {{ $loan->loan_application->purpose }}</p>
            </div>
            <div class="leading-4">
                <p>Type of Loan: {{ $loan->loan_type->name }}</p>
                <p>Terms of Payment: {{ $loan->number_of_terms }}</p>
                <p>Interest Rate: {{ $loan->interest_rate * 100 }}%</p>
                <p>Monthly Amortization: {{ number_format($loan->monthly_payment, 2) }}</p>
                <p>Interest Due: {{ number_format($this->accrued_interest, 4) }}</p>
            </div>
        </div>
        <div class="mt-4">
            <table class="w-full print:text-[8pt]">
                <thead>
                    <tr>
                        <th class="border border-black px-4">Date</th>
                        <th class="border border-black px-4">Reference Number</th>
                        <th class="border border-black px-4">Amortization</th>
                        <th class="border border-black px-4">Surcharge</th>
                        <th class="border border-black px-4">Interest</th>
                        <th class="border border-black px-4">Payment Principal</th>
                        <th class="border border-black px-4">Outstanding Balance</th>
                        <th class="border border-black px-4">Remarks/Initial</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $running_balance = $loan->gross_amount;
                    @endphp
                    @forelse ($loan->payments as $payment)
                        @php
                            $running_balance = round($running_balance - $payment->principal_payment, 4);
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap border border-black px-4">
                                {{ $payment->transaction_date->format('F d, Y') }}</td>
                            <td class="border border-black px-4 text-center">{{ $payment->reference_number }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">
                                {{ number_format($payment->amount, 4) }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right"></td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">
                                {{ number_format($payment->interest_payment, 4) }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">
                                {{ number_format($payment->principal_payment, 4) }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">
                                {{ number_format($running_balance, 4) }}</td>
                            <td class="border border-black px-4">{{ $payment->remarks }}</td>
                        </tr>
                    @empty
                        <tr class="border border-black">
                            <td colspan="8" class="text-center">No payments made.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td class="border border-black px-4">Total</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">
                            {{ number_format($loan->payments->sum('amount'), 2) }}</td>
                        <td class="border border-black px-4 text-right">{{ number_format(0, 2) }}</td>
                        <td class="border border-black px-4 text-right">
                            {{ number_format($loan->payments->sum('interest_payment'), 2) }}</td>
                        <td class="border border-black px-4 text-right">
                            {{ number_format($loan->payments->sum('principal_payment'), 2) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between">
            <div class="flex">
                <p>Prepared by:</p>
                <div class="ml-4 mt-12 flex flex-col items-center">
                    <p class="font-bold uppercase">{{ auth()->user()->name }}</p>
                    <p>Loan Officer</p>
                </div>
            </div>
            <div class="mr-8 mt-4 grid grid-cols-2 gap-x-4 leading-4">
                <p>PRINCIPAL</p>
                <p class="text-right">{{ number_format($loan->payments->sum('principal_payment'), 2) }}</p>
                <p>INTEREST</p>
                <p class="text-right">{{ number_format($loan->payments->sum('interest_payment'), 2) }}</p>
                <p>SURCHARGE</p>
                <p class="text-right">{{ number_format(0, 2) }}</p>
                <p>&nbsp;</p>
                <p class="border-t-2 border-black text-right">{{ number_format($loan->payments->sum('principal_payment') + $loan->payments->sum('interest_payment') + 0, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-2">
        <x-filament::button wire:ignore href="{{ back()->getTargetUrl() }}" outlined tag="a">Back</x-filament::button>
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</x-filament-panels::page>
