@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div>
        <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
            <x-app.cashier.reports.report-heading />
            <div>
                <h3>Member's Name: {{ $cbu->member->full_name }}</h3>
                <h3>Amount Subscribed: {{ format_money($cbu->amount_subscribed, 'PHP') }}</h3>
                <h3>Transaction Date: {{ $cbu->transaction_date->format('M d, Y') }}</h3>
                <h3>Terms of Payment: {{ $cbu->number_of_terms }}</h3>
            </div>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="border border-black px-2 text-left">No.</th>
                        <th class="border border-black px-2 text-left">Month</th>
                        <th class="border border-black px-2 text-right">Amount Due</th>
                        <th class="border border-black px-2 text-right">Amount Paid</th>
                        <th class="border border-black px-2 text-right">Arrears</th>
                        <th class="border border-black px-2">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black px-2"></td>
                        <td class="border whitespace-nowrap border-black px-2">Initial Payment</td>
                        <td class="border border-black px-2 text-right">{{ format_money($cbu->initial_amount_paid, 'PHP') }}</td>
                        <td class="border border-black px-2 text-right"></td>
                        <td class="border border-black px-2 text-right"></td>
                        <td class="border border-black px-2"></td>
                    </tr>
                    @foreach ($cbu->capital_subscription_amortizations as $amortization)
                        <tr>
                            <td class="border border-black px-2">{{ $amortization->term }}</td>
                            <td class="border whitespace-nowrap border-black px-2">{{ $amortization->due_date->format('F Y') }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($amortization->amount, 'PHP') }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($amortization->amount_paid ?? 0, 'PHP') }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($amortization->arrears ?? 0, 'PHP') }}</td>
                            <td class="border border-black px-2"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border border-black px-2"></td>
                        <td class="border border-black px-2">TOTAL</td>
                        <td class="border border-black px-2 text-right">{{ format_money($cbu->amount_subscribed, 'PHP') }}</td>
                        <td class="border border-black px-2 text-right">{{ format_money($cbu->capital_subscription_amortizations->sum('amount_paid'), 'PHP') }}</td>
                        <td class="border border-black px-2 text-right">{{ format_money($cbu->capital_subscription_amortizations->sum('arrears'), 'PHP') }}</td>
                        <td class="border border-black px-2"></td>
                    </tr>
                </tbody>
            </table>
            <x-app.cashier.reports.signatories :signatories="$signatories" />

        </div>
        <div class="flex justify-end space-x-2">
            <x-filament::button href="{{ route('filament.app.resources.members.view', ['record' => $cbu->member, 'tab' => '-cbu-tab']) }}" tag="a">Back to CBU</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Amortization Schedule')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
