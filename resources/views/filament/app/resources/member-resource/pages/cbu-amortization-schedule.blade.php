<x-filament-panels::page>
    <x-app.cashier.reports.report-layout title="CBU Amortization Schedule" :signatories="$this->getSignatories()">
        <div>
            <h3>Member's Name: {{ $cbu->member->full_name }}</h3>
            <h3>Amount Subscribed: {{ renumber_format($cbu->amount_subscribed) }}</h3>
            <h3>Transaction Date: {{ $cbu->transaction_date->format('M d, Y') }}</h3>
            <h3>Terms of Payment: {{ $cbu->number_of_terms }}</h3>
        </div>
        <table class="mt-4 w-full">
            <thead>
                <tr>
                    <th class="border border-black px-2 text-left">No.</th>
                    <th class="border border-black px-2 text-left">Month</th>
                    <th class="border border-black px-2 text-right">Amount Due</th>
                    <th class="border border-black px-2">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black px-2"></td>
                    <td class="whitespace-nowrap border border-black px-2">Initial Payment</td>
                    <td class="border border-black px-2 text-right">{{ renumber_format($cbu->initial_amount_paid) }}</td>
                    <td class="border border-black px-2"></td>
                </tr>
                @php
                    $amortizations = App\Oxytoxin\Providers\ShareCapitalProvider::generateAmortizationSchedule($cbu);
                @endphp
                @foreach ($amortizations as $amortization)
                    <tr>
                        <td class="border border-black px-2">{{ $amortization['term'] }}</td>
                        <td class="whitespace-nowrap border border-black px-2">{{ $amortization['due_date']->format('F Y') }}</td>
                        <td class="border border-black px-2 text-right">{{ renumber_format($amortization['amount']) }}</td>
                        <td class="border border-black px-2"></td>
                    </tr>
                @endforeach
                <tr>
                    <td class="border border-black px-2"></td>
                    <td class="border border-black px-2 font-bold">TOTAL</td>
                    <td class="border border-black px-2 text-right font-bold">{{ renumber_format($cbu->amount_subscribed) }}</td>
                    <td class="border border-black px-2"></td>
                </tr>
            </tbody>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
