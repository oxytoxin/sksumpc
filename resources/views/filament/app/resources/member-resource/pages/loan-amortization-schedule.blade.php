@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div>
        <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
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
            <table class="mt-4 w-full">
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
                        <td class="border border-black px-2 print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 text-right print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 text-right print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 text-right print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 text-right print:border-x print:border-y-0">{{ number_format($loan->gross_amount, 2) }}</td>
                        <td class="border border-black px-2 print:border-x print:border-y-0"></td>
                    </tr>
                    @php
                        $ob = $loan->gross_amount;
                        $amortizations = App\Oxytoxin\Providers\LoansProvider::generateAmortizationSchedule($loan);
                    @endphp
                    @foreach ($amortizations as $loan_amortization)
                        @php
                            $ob -= $loan_amortization['principal'];
                        @endphp
                        <tr>
                            <td class="border border-black px-2 print:border-x print:border-y-0">{{ $loan_amortization['term'] }}</td>
                            <td class="whitespace-nowrap border border-black px-2 print:border-x print:border-y-0">{{ $loan_amortization['date']->format('F Y') }}</td>
                            <td class="border border-black px-2 text-center print:border-x print:border-y-0">{{ $loan_amortization['days'] }}</td>
                            <td class="border border-black px-2 text-right print:border-x print:border-y-0">{{ number_format($loan_amortization['amortization'], 2) }}</td>
                            <td class="border border-black px-2 text-right print:border-x print:border-y-0">{{ number_format($loan_amortization['interest'], 2) }}</td>
                            <td class="border border-black px-2 text-right print:border-x print:border-y-0">{{ number_format($loan_amortization['principal'], 2) }}</td>
                            <td class="border border-black px-2 text-right print:border-x print:border-y-0">{{ number_format($loan_amortization['outstanding_balance'] ?? $ob, 2) }}</td>
                            <td class="border border-black px-2 print:border-x print:border-y-0"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border border-black px-2 font-bold print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 font-bold print:border-x print:border-y-0">TOTAL</td>
                        <td class="border border-black px-2 text-center font-bold print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 text-right font-bold print:border-x print:border-y-0">{{ number_format(collect($amortizations)->sum('amortization'), 2) }}</td>
                        <td class="border border-black px-2 text-right font-bold print:border-x print:border-y-0">{{ number_format(collect($amortizations)->sum('interest'), 2) }}</td>
                        <td class="border border-black px-2 text-right font-bold print:border-x print:border-y-0">{{ number_format(collect($amortizations)->sum('principal'), 2) }}</td>
                        <td class="border border-black px-2 text-right font-bold print:border-x print:border-y-0"></td>
                        <td class="border border-black px-2 font-bold print:border-x print:border-y-0"></td>
                    </tr>
                </tbody>
            </table>
            <x-app.cashier.reports.signatories :signatories="$this->getSignatories()" />
        </div>
        <div class="flex justify-end space-x-2">
            <x-filament::button href="{{ back()->getTargetUrl() }}" wire:ignore outlined tag="a">Back</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Loan Amortization Schedule')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
