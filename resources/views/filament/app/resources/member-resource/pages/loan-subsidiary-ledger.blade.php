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
                <p>Monthly Amortization: {{ renumber_format($loan->monthly_payment, 2) }}</p>
                <p>Interest Due: {{ renumber_format($this->accrued_interest, 2) }}</p>
            </div>
        </div>

        <div class="mt-4">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th class="doc-table-header-cell">Date</th>
                        <th class="doc-table-header-cell">Reference Number</th>
                        <th class="doc-table-header-cell">Amortization</th>
                        <th class="doc-table-header-cell">Surcharge</th>
                        <th class="doc-table-header-cell">Interest</th>
                        <th class="doc-table-header-cell">Payment Principal</th>
                        <th class="doc-table-header-cell">Outstanding Balance</th>
                        <th class="doc-table-header-cell">Remarks/Initial</th>
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
                            <td class="doc-table-cell">
                                {{ $payment->transaction_date->format('F d, Y') }}
                            </td>
                            <td class="doc-table-cell-center">{{ $payment->reference_number }}</td>
                            <td class="doc-table-cell-right">
                                {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="doc-table-cell-right"></td>
                            <td class="doc-table-cell-right">
                                {{ number_format($payment->interest_payment, 2) }}
                            </td>
                            <td class="doc-table-cell-right">
                                {{ number_format($payment->principal_payment, 2) }}
                            </td>
                            <td class="doc-table-cell-right">
                                {{ number_format($running_balance, 2) }}
                            </td>
                            <td class="doc-table-cell">{{ $payment->remarks }}</td>
                        </tr>
                    @empty
                        <tr class="border border-black">
                            <td class="text-center" colspan="8">No payments made.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td class="doc-table-cell">Total</td>
                        <td class="doc-table-cell"></td>
                        <td class="doc-table-cell-right">
                            {{ number_format($loan->payments->sum('amount'), 2) }}
                        </td>
                        <td class="doc-table-cell-right">{{ number_format(0, 2) }}</td>
                        <td class="doc-table-cell-right">
                            {{ number_format($loan->payments->sum('interest_payment'), 2) }}
                        </td>
                        <td class="doc-table-cell-right">
                            {{ number_format($loan->payments->sum('principal_payment'), 2) }}
                        </td>
                        <td class="doc-table-cell"></td>
                        <td class="doc-table-cell"></td>
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
        <x-filament::button href="{{ back()->getTargetUrl() }}" wire:ignore outlined tag="a">Back</x-filament::button>
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</x-filament-panels::page>
