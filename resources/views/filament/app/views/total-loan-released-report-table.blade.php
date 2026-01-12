@php
    use function Filament\Support\format_money;

    if (isset($this->tableFilters['release_date']['release_date'])) {
        [$from, $to] = explode(' - ', $this->tableFilters['release_date']['release_date']);
        $date_from = date_create($from);
        $date_to = date_create($to);
        $one_day = $from == $to;
    } else {
        $date_from = date_create(config('app.transaction_date'));
        $date_to = date_create(config('app.transaction_date'));
        $one_day = true;
    }
@endphp
<div x-data>
    <div class="p-4 text-sm print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="mt-4 text-center text-3xl font-bold print:text-[14pt]">TOTAL LOAN RELEASED</h4>
        @if ($one_day)
            <h5 class="text-center font-semibold uppercase">{{ $date_from->format('F d, Y') }}</h5>
        @else
            <h5 class="text-center font-semibold uppercase">{{ $date_from->format('F d, Y') }} to {{ $date_to->format('F d, Y') }}</h5>
        @endif
        <h5 class="mt-8 text-center font-semibold uppercase" wire:loading.block>Refreshing data...</h5>
        <table class="doc-table mx-auto mt-8 overflow-auto print:text-[8pt]" id="loan_report" wire:loading.remove>
            <thead>
                <tr>
                    <th class="doc-table-header-cell" rowspan="2">No.</th>
                    <th class="doc-table-header-cell" rowspan="2">Priority Number</th>
                    <th class="doc-table-header-cell" rowspan="2">Name of Borrower</th>
                    <th class="doc-table-header-cell" rowspan="2">Gender</th>
                    <th class="doc-table-header-cell" colspan="{{ $loan_types->count() + 1 }}">GROSS AMOUNT</th>
                    <th class="doc-table-header-cell" colspan="{{ $loan_types->count() + 1 }}">NET AMOUNT</th>
                    <th class="doc-table-header-cell" rowspan="2">Received</th>
                </tr>
                <tr>
                    <th class="doc-table-header-cell">GROSS TOTAL</th>
                    @foreach ($loan_types as $loan_type)
                        <th class="doc-table-header-cell">{{ $loan_type->code }}</th>
                    @endforeach
                    @foreach ($loan_types as $loan_type)
                        <th class="doc-table-header-cell">{{ $loan_type->code }}</th>
                    @endforeach
                    <th class="doc-table-header-cell">NET PROCEEDS</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $records = $this->table->getRecords();
                    $member_loans = $records->sortBy('member.alt_full_name')->groupBy('member_id');
                    $members = App\Models\Member::findMany($member_loans->keys())->mapWithKeys(fn($m) => [$m->id => $m]);
                    $gross_amount = $records->sum('gross_amount');
                    $net_amount = $records->sum('net_amount');
                @endphp
                @foreach ($member_loans as $member_id => $loans)
                    <tr>
                        <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                        <th class="doc-table-cell-center">{{ $loans->sortByDesc('transaction_date')->first()->priority_number }}</th>
                        <td class="doc-table-cell">{{ $members[$member_id]->alt_full_name }}</td>
                        <td class="doc-table-cell">{{ $members[$member_id]->gender?->name }}</td>
                        <td class="doc-table-cell-right">{{ number_format($loans->sum('gross_amount'), 2) }}</td>
                        @foreach ($loan_types as $loan_type)
                            <td class="doc-table-cell-right">{{ $loans->where('loan_type_id', $loan_type->id)->sum('gross_amount') ? number_format($loans->where('loan_type_id', $loan_type->id)->sum('gross_amount'), 2) : '' }}</td>
                        @endforeach
                        @foreach ($loan_types as $loan_type)
                            <td class="doc-table-cell-right">{{ $loans->where('loan_type_id', $loan_type->id)->sum('net_amount') ? number_format($loans->where('loan_type_id', $loan_type->id)->sum('net_amount'), 2) : '' }}</td>
                        @endforeach
                        <td class="doc-table-cell-right">{{ number_format($loans->sum('net_amount'), 2) }}</td>
                        <td class="doc-table-cell-right"></td>
                    </tr>
                @endforeach
                <tr class="doc-table-row-total">
                    <td class="doc-table-cell-center">&nbsp;</td>
                    <td class="doc-table-cell-center">&nbsp;</td>
                    <td class="doc-table-cell-center">&nbsp;</td>
                    <td class="doc-table-cell-right">{{ number_format($gross_amount, 2) }}</td>
                    @foreach ($loan_types as $loan_type)
                        <th class="doc-table-cell-right">{{ $records->where('loan_type_id', $loan_type->id)->sum('gross_amount') ? number_format($records->where('loan_type_id', $loan_type->id)->sum('gross_amount'), 2) : '' }}</th>
                    @endforeach
                    @foreach ($loan_types as $loan_type)
                        <th class="doc-table-cell-right">{{ $records->where('loan_type_id', $loan_type->id)->sum('net_amount') ? number_format($records->where('loan_type_id', $loan_type->id)->sum('net_amount'), 2) : '' }}</th>
                    @endforeach
                    <th class="doc-table-cell-right">{{ number_format($net_amount, 2) }}</th>
                    <td class="doc-table-cell-center">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$this->getSignatories()" />
    </div>
    <div class="flex justify-end gap-4 p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'TOTAL LOAN RELEASED')">Print</x-filament::button>
    </div>

</div>
