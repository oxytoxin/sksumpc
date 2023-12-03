@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="text-3xl text-center mt-4 print:text-[14pt] font-bold">TOTAL LOAN RELEASED</h4>
        @if ($this->tableFilters['release_date']['from'])
            <h5 class="text-center uppercase">{{ date_create($this->tableFilters['release_date']['from'])->format('F d, Y') }} to {{ date_create($this->tableFilters['release_date']['to'] ?? today())->format('F d, Y') }}</h5>
        @else
            <h5 class="text-center uppercase">{{ date_create($this->tableFilters['release_date']['to'] ?? today())->format('F d, Y') }}</h5>
        @endif
        <table class="overflow-auto print:text-[10pt] w-full">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center border-2 border-black">No.</th>
                    <th rowspan="2" class="text-center border-2 border-black">Name of Borrower</th>
                    <th colspan="{{ $loan_types->count() + 1 }}" class="text-center border-2 border-black">GROSS AMOUNT</th>
                    <th colspan="{{ $loan_types->count() + 1 }}" class="text-center border-2 border-black">NET PROCEEDS</th>
                </tr>
                <tr>
                    <th class="text-center border-2 border-black whitespace-nowrap px-4">GROSS TOTAL</th>
                    @foreach ($loan_types as $loan_type)
                        <th class="text-center border-2 border-black whitespace-nowrap px-4">{{ $loan_type->code }}</th>
                    @endforeach
                    @foreach ($loan_types as $loan_type)
                        <th class="text-center border-2 border-black whitespace-nowrap px-4">{{ $loan_type->code }}</th>
                    @endforeach
                    <th class="text-center border-2 border-black whitespace-nowrap px-4">NET TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $records = $this->table->getRecords();
                    $members = $records->groupBy('member.alt_full_name')->ksort();
                    $gross_amount = $records->sum('gross_amount');
                    $net_amount = $records->sum('net_amount');
                @endphp
                @foreach ($members as $member => $loans)
                    <tr>
                        <th class="text-center px-4 border-2 border-black whitespace-nowrap">{{ $loop->iteration }}</th>
                        <td class="text-left px-4 border-2 border-black whitespace-nowrap">{{ $member }}</td>
                        <td class="text-right px-4 border-2 border-black whitespace-nowrap">{{ number_format($loans->sum('gross_amount'), 2) }}</td>
                        @foreach ($loan_types as $loan_type)
                            <td class="text-right px-4 border-2 border-black whitespace-nowrap">{{ $loans->where('loan_type_id', $loan_type->id)->sum('gross_amount') ? number_format($loans->where('loan_type_id', $loan_type->id)->sum('gross_amount'), 2) : '' }}</td>
                        @endforeach
                        @foreach ($loan_types as $loan_type)
                            <td class="text-right px-4 border-2 border-black whitespace-nowrap">{{ $loans->where('loan_type_id', $loan_type->id)->sum('net_amount') ? number_format($loans->where('loan_type_id', $loan_type->id)->sum('net_amount'), 2) : '' }}</td>
                        @endforeach
                        <td class="text-right px-4 border-2 border-black whitespace-nowrap">{{ number_format($loans->sum('net_amount'), 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th class="text-center px-4 border-2 border-black whitespace-nowrap">&nbsp;</td>
                    <th class="text-center px-4 border-2 border-black whitespace-nowrap">&nbsp;</td>
                    <th class="text-right px-4 border-2 border-black whitespace-nowrap">{{ number_format($gross_amount, 2) }}</th>
                    @foreach ($loan_types as $loan_type)
                        <th class="text-right px-4 border-2 border-black whitespace-nowrap">{{ $records->where('loan_type_id', $loan_type->id)->sum('gross_amount') ? number_format($records->where('loan_type_id', $loan_type->id)->sum('gross_amount'), 2) : '' }}</th>
                    @endforeach
                    @foreach ($loan_types as $loan_type)
                        <th class="text-right px-4 border-2 border-black whitespace-nowrap">{{ $records->where('loan_type_id', $loan_type->id)->sum('net_amount') ? number_format($records->where('loan_type_id', $loan_type->id)->sum('net_amount'), 2) : '' }}</th>
                    @endforeach
                    <th class="text-right px-4 border-2 border-black whitespace-nowrap">{{ number_format($net_amount, 2) }}</th>
                </tr>
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
