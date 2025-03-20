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
        <table class="w-full overflow-auto print:text-[10pt]">
            <thead>
                <tr>
                    <th class="border-2 border-black text-center" rowspan="2">No.</th>
                    <th class="border-2 border-black text-center" rowspan="2">Full Name</th>
                    <th class="border-2 border-black text-center" rowspan="2">OR Number</th>
                    @foreach ($loan_types as $loan_type)
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center" colspan="3">
                            {{ $loan_type->code }}</th>
                    @endforeach
                    <th class="border-2 border-black text-center" rowspan="2">TOTAL</th>
                </tr>
                <tr>
                    @foreach ($loan_types as $loan_type)
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">PRINCIPAL</th>
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">INTEREST</th>
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">SURCHARGE</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($this->table->getRecords() as $loan_payment)
                    <tr>
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">{{ $loop->iteration }}</th>
                        <td class="whitespace-nowrap border-2 border-black px-4 text-left">
                            {{ $loan_payment->loan->member->alt_full_name }}</td>
                        <td class="whitespace-nowrap border-2 border-black px-4 text-right">
                            {{ $loan_payment->reference_number }}</td>
                        @foreach ($loan_types as $loan_type)
                            <td class="whitespace-nowrap border-2 border-black px-4 text-right">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? number_format($loan_payment->principal_payment, 2) : '' }}
                            </td>
                            <td class="whitespace-nowrap border-2 border-black px-4 text-right">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? number_format($loan_payment->interest, 2) : '' }}
                            </td>
                            <td class="whitespace-nowrap border-2 border-black px-4 text-right">
                                {{ $loan_payment->loan->loan_type_id == $loan_type->id ? '' : '' }}</td>
                        @endforeach
                        <td class="whitespace-nowrap border-2 border-black px-4 text-right font-bold">
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
