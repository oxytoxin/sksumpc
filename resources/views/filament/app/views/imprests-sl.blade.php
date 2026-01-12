@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="mt-4 text-center text-3xl font-bold print:text-[14pt]">SUBSIDIARY LEDGER FOR IMPRESTS</h4>
        <div class="my-4">
            <h4>Name: {{ $member->full_name }}</h4>
            <h4>Campus: {{ $member->division?->name }}</h4>
            <h4>Account Number: {{ $member->imprest_account?->number }}</h4>
        </div>
        <table class="doc-table">
            <thead>
                <tr>
                    <th class="doc-table-header-cell">DATE</th>
                    <th class="doc-table-header-cell">REF. #</th>
                    <th class="doc-table-header-cell">DR</th>
                    <th class="doc-table-header-cell">CR</th>
                    <th class="doc-table-header-cell">OUTSTANDING BALANCE</th>
                    <th class="doc-table-header-cell">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($this->table->getRecords() as $record)
                    <tr>
                        <th class="doc-table-cell">
                            {{ $record->transaction_date->format('m/d/Y') }}</th>
                        <td class="doc-table-cell">{{ $record->reference_number }}</td>
                        <td class="doc-table-cell-right">
                            {{ $record->withdrawal ? number_format($record->withdrawal, 2) : '' }}</td>
                        <td class="doc-table-cell-right">
                            {{ $record->deposit ? number_format($record->deposit, 2) : '' }}</td>
                        @php
                            $total += $record->amount;
                        @endphp
                        <td class="doc-table-cell-right">{{ number_format($total, 2) }}</td>
                        <td class="doc-table-cell-center">{{ $record->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$this->getSignatories()" />
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Imprests Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
