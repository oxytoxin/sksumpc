@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="mt-4 text-center text-3xl font-bold print:text-[14pt]">SUBSIDIARY LEDGER FOR LOVE GIFTS</h4>
        <div class="my-4">
            <h4>Name: {{ $member->full_name }}</h4>
            <h4>Campus: {{ $member->division?->name }}</h4>
            <h4>Account Number:</h4>
        </div>
        <table class="w-full print:text-[10pt]">
            <thead>
                <tr>
                    <th class="border-2 border-black text-center">DATE</th>
                    <th class="border-2 border-black text-center">REF. #</th>
                    <th class="border-2 border-black px-6 text-center">DR</th>
                    <th class="border-2 border-black text-center">CR</th>
                    <th class="border-2 border-black text-center">OUTSTANDING BALANCE</th>
                    <th class="border-2 border-black px-4 text-center">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($this->table->getRecords() as $record)
                    <tr>
                        <th class="border-2 border-black px-4 text-left">
                            {{ $record->transaction_date->format('m/d/Y') }}</th>
                        <td class="border-2 border-black px-4 text-left">{{ $record->reference_number }}</td>
                        <td class="border-2 border-black px-4 text-right">
                            {{ $record->withdrawal ? number_format($record->withdrawal, 2) : '' }}</td>
                        <td class="border-2 border-black px-4 text-right">
                            {{ $record->deposit ? number_format($record->deposit, 2) : '' }}</td>
                        @php
                            $total += $record->amount;
                        @endphp
                        <td class="border-2 border-black px-4 text-right">{{ number_format($total, 2) }}</td>
                        <td class="border-2 border-black text-center">{{ $record->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Love Gifts Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
