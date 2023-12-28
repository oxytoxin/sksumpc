@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="text-3xl text-center mt-4 print:text-[14pt] font-bold">DAILY COLLECTION REPORT</h4>
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
        <table class="overflow-auto print:text-[10pt] w-full">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center border-2 border-black">No.</th>
                    <th rowspan="2" class="text-center border-2 border-black">Full Name</th>
                    <th rowspan="2" class="text-center border-2 border-black">OR Number</th>
                    @foreach ($loan_types as $loan_type)
                        <th colspan="3" class="text-center border-2 border-black whitespace-nowrap px-4">
                            {{ $loan_type->code }}</th>
                    @endforeach
                    <th rowspan="2" class="text-center border-2 border-black">TOTAL</th>
                </tr>
                <tr>
                    @foreach ($loan_types as $loan_type)
                        <th class="text-center border-2 border-black whitespace-nowrap px-4">PRINCIPAL</th>
                        <th class="text-center border-2 border-black whitespace-nowrap px-4">INTEREST</th>
                        <th class="text-center border-2 border-black whitespace-nowrap px-4">SURCHARGE</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($this->table->getRecords() as $loan_payment)
                    <tr>
                        <th class="text-center px-4 border-2 border-black whitespace-nowrap">{{ $loop->iteration }}</th>
                        <td class="text-left px-4 border-2 border-black whitespace-nowrap">
                            {{ $loan_payment->loan->member->alt_full_name }}</td>
                        <td class="text-right px-4 border-2 border-black whitespace-nowrap">
                            {{ $loan_payment->reference_number }}</td>
                        @foreach ($loan_types as $loan_type)
                            <td class="text-right px-4 border-2 border-black whitespace-nowrap">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? number_format($loan_payment->principal_payment, 2) : '' }}
                            </td>
                            <td class="text-right px-4 border-2 border-black whitespace-nowrap">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? number_format($loan_payment->interest, 2) : '' }}
                            </td>
                            <td class="text-right px-4 border-2 border-black whitespace-nowrap">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? '' : '' }}</td>
                        @endforeach
                        <td class="text-right px-4 border-2 border-black whitespace-nowrap font-bold">
                            {{ number_format($loan_payment->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer"
            @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
