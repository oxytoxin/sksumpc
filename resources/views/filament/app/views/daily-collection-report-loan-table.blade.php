@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="mt-4 text-center text-3xl font-bold print:text-[14pt]">DAILY COLLECTION REPORT</h4>
        @if ($this->tableFilters['release_date']['from'])
            <h5 class="text-center uppercase">
                {{ date_create($this->tableFilters['release_date']['from'])->format('F d, Y') }} to
                {{ date_create($this->tableFilters['release_date']['to'] ?? today())->format('F d, Y') }}
            </h5>
        @else
            <h5 class="text-center uppercase">
                {{ date_create($this->tableFilters['release_date']['to'] ?? today())->format('F d, Y') }}
            </h5>
        @endif
        <table class="doc-table overflow-auto print:text-[10pt]">
            <thead>
                <tr>
                    <th class="doc-table-header-cell" rowspan="2">No.</th>
                    <th class="doc-table-header-cell" rowspan="2">Full Name</th>
                    <th class="doc-table-header-cell" rowspan="2">OR Number</th>
                    @foreach ($loan_types as $loan_type)
                        <th class="doc-table-header-cell" colspan="3">
                            {{ $loan_type->code }}</th>
                    @endforeach
                    <th class="doc-table-header-cell" rowspan="2">TOTAL</th>
                </tr>
                <tr>
                    @foreach ($loan_types as $loan_type)
                        <th class="doc-table-header-cell">PRINCIPAL</th>
                        <th class="doc-table-header-cell">INTEREST</th>
                        <th class="doc-table-header-cell">SURCHARGE</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($this->table->getRecords() as $loan_payment)
                    <tr>
                        <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                        <td class="doc-table-cell">
                            {{ $loan_payment->loan->member->alt_full_name }}</td>
                        <td class="doc-table-cell-right">
                            {{ $loan_payment->reference_number }}</td>
                        @foreach ($loan_types as $loan_type)
                            <td class="doc-table-cell-right">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? number_format($loan_payment->principal_payment, 2) : '' }}
                            </td>
                            <td class="doc-table-cell-right">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? number_format($loan_payment->interest, 2) : '' }}
                            </td>
                            <td class="doc-table-cell-right">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? '' : '' }}</td>
                        @endforeach
                        <td class="doc-table-cell-right font-bold">
                            {{ number_format($loan_payment->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$this->getSignatories()" />
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
