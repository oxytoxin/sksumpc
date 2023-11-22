@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div>
        <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
            <x-app.cashier.reports.report-heading />
            <div>
                <h3>Member's Name: {{ $loan->member->full_name }}</h3>
                <h3>Loan Type: {{ $loan->loan_type->name }}</h3>
                <h3>Amount Granted: {{ format_money($loan->gross_amount, 'PHP') }}</h3>
                <h3>Date Granted: {{ $loan->transaction_date->format('M d, Y') }}</h3>
                <h3>Maturity Date: {{ $loan->maturity_date->format('M d, Y') }}</h3>
                <h3>Terms of Payment: {{ $loan->number_of_terms }}</h3>
                <h3>Interest Rate: {{ $loan->interest_rate * 100 }}%</h3>
            </div>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="border border-black px-2 text-left">No.</th>
                        <th class="border border-black px-2 text-left">Month</th>
                        <th class="border border-black px-2">Days</th>
                        <th class="border border-black px-2 text-right">Regular Amortization</th>
                        <th class="border border-black px-2 text-right">Interest</th>
                        <th class="border border-black px-2 text-right">Principal</th>
                        <th class="border border-black px-2 text-right">Outstanding Balance</th>
                        <th class="border border-black px-2">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black px-2"></td>
                        <td class="border border-black px-2"></td>
                        <td class="border border-black px-2"></td>
                        <td class="border border-black px-2 text-right"></td>
                        <td class="border border-black px-2 text-right"></td>
                        <td class="border border-black px-2 text-right"></td>
                        <td class="border border-black px-2 text-right">{{ format_money($loan->gross_amount, 'PHP') }}</td>
                        <td class="border border-black px-2"></td>
                    </tr>
                    @foreach ($loan->loan_amortizations as $loan_amortization)
                        <tr>
                            <td class="border border-black px-2">{{ $loan_amortization->term }}</td>
                            <td class="border whitespace-nowrap border-black px-2">{{ $loan_amortization->date->format('F Y') }}</td>
                            <td class="border border-black px-2 text-center">{{ $loan_amortization->days }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->amortization, 'PHP') }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->interest, 'PHP') }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->principal, 'PHP') }}</td>
                            <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->outstanding_balance, 'PHP') }}</td>
                            <td class="border border-black px-2"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border border-black px-2"></td>
                        <td class="border border-black px-2">TOTAL</td>
                        <td class="border border-black px-2 text-center"></td>
                        <td class="border border-black px-2 text-right">{{ format_money($loan->loan_amortizations->sum('amortization'), 'PHP') }}</td>
                        <td class="border border-black px-2 text-right">{{ format_money($loan->loan_amortizations->sum('interest'), 'PHP') }}</td>
                        <td class="border border-black px-2 text-right">{{ format_money($loan->loan_amortizations->sum('principal'), 'PHP') }}</td>
                        <td class="border border-black px-2 text-right"></td>
                        <td class="border border-black px-2"></td>
                    </tr>
                </tbody>
            </table>
            <x-app.cashier.reports.signatories :signatories="$signatories" />

        </div>
        <div class="flex justify-end space-x-2">
            <x-filament::button href="{{ route('filament.app.resources.members.view', ['record' => $loan->member, 'tab' => '-loan-tab']) }}" tag="a">Back to Loans</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Loan Amortization Schedule')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
