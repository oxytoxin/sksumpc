@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="text-3xl text-center mt-4 print:text-[14pt] font-bold">SUBSIDIARY LEDGER FOR SAVINGS</h4>
        <div class="my-4">
            <h4>Name: {{ $savings_account->member->full_name }}</h4>
            <h4>Campus: {{ $savings_account->member->division?->name }}</h4>
            <h4>Account Number: {{ $savings_account->number }}</h4>
        </div>
        <table class="w-full print:text-[10pt]">
            <thead>
                <tr>
                    <th class="text-center border-2 border-black">DATE</th>
                    <th class="text-center border-2 border-black">REF. #</th>
                    <th class="text-center border-2 border-black px-6">DR</th>
                    <th class="text-center border-2 border-black">CR</th>
                    <th class="text-center border-2 border-black">OUTSTANDING BALANCE</th>
                    <th class="text-center border-2 border-black px-4">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($this->table->getRecords() as $record)
                    <tr>
                        <th class="text-left px-4 border-2 border-black">
                            {{ $record->transaction_date->format('m/d/Y') }}</th>
                        <td class="text-left px-4 border-2 border-black">{{ $record->reference_number }}</td>
                        <td class="text-right px-4 border-2 border-black">
                            {{ renumber_format($record->debit, 2) }}</td>
                        <td class="text-right px-4 border-2 border-black">
                            {{ renumber_format($record->credit, 2) }}</td>
                        @php
                            $total -= $record->debit;
                            $total += $record->credit;
                        @endphp
                        <td class="text-right px-4 border-2 border-black">{{ number_format($total, 2) }}</td>
                        <td class="text-center border-2 border-black">{{ $record->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer"
            @click="printOut($refs.print.outerHTML, 'Savings Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
