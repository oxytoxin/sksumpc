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
            <h5 class="text-center uppercase font-semibold">{{ $date_from->format('F d, Y') }}</h5>
        @else
            <h5 class="text-center uppercase font-semibold">{{ $date_from->format('F d, Y') }} to {{ $date_to->format('F d, Y') }}</h5>
        @endif
        <h5 wire:loading.block class="text-center mt-8 uppercase font-semibold">Refreshing data...</h5>
        <table wire:loading.remove class="w-full overflow-auto mt-8 print:text-[8pt]">
            <thead>
                <tr>
                    <th rowspan="2" class="border-2 border-black text-center">No.</th>
                    <th rowspan="2" class="border-2 border-black text-center">Name of Borrower</th>
                    <th rowspan="2" class="border-2 border-black text-center">Gender</th>
                    <th colspan="{{ $loan_types->count() + 1 }}" class="border-2 border-black text-center">GROSS AMOUNT</th>
                    <th colspan="{{ $loan_types->count() + 1 }}" class="border-2 border-black text-center">NET AMOUNT</th>
                </tr>
                <tr>
                    <th class="whitespace-nowrap border-2 border-black px-4 text-center">GROSS TOTAL</th>
                    @foreach ($loan_types as $loan_type)
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">{{ $loan_type->code }}</th>
                    @endforeach
                    @foreach ($loan_types as $loan_type)
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">{{ $loan_type->code }}</th>
                    @endforeach
                    <th class="border-2 border-black text-center">NET PROCEEDS</th>

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
                        <th class="whitespace-nowrap border-2 border-black px-4 text-center">{{ $loop->iteration }}</th>
                        <td class="whitespace-nowrap border-2 border-black px-4 text-left">{{ $members[$member_id]->alt_full_name }}</td>
                        <td class="whitespace-nowrap border-2 border-black px-4 text-left">{{ $members[$member_id]->gender?->name }}</td>
                        <td class="whitespace-nowrap border-2 border-black px-4 text-right">{{ number_format($loans->sum('gross_amount'), 2) }}</td>
                        @foreach ($loan_types as $loan_type)
                            <td class="whitespace-nowrap border-2 border-black px-4 text-right">{{ $loans->where('loan_type_id', $loan_type->id)->sum('gross_amount') ? number_format($loans->where('loan_type_id', $loan_type->id)->sum('gross_amount'), 2) : '' }}</td>
                        @endforeach
                        @foreach ($loan_types as $loan_type)
                            <td class="whitespace-nowrap border-2 border-black px-4 text-right">{{ $loans->where('loan_type_id', $loan_type->id)->sum('net_amount') ? number_format($loans->where('loan_type_id', $loan_type->id)->sum('net_amount'), 2) : '' }}</td>
                        @endforeach
                        <td class="whitespace-nowrap border-2 border-black px-4 text-right">{{ number_format($loans->sum('net_amount'), 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="whitespace-nowrap border-2 border-black px-4 text-center">&nbsp;</td>
                    <td class="whitespace-nowrap border-2 border-black px-4 text-center">&nbsp;</td>
                    <td class="whitespace-nowrap border-2 border-black px-4 text-center">&nbsp;</td>
                    <td class="whitespace-nowrap border-2 border-black px-4 text-right">{{ number_format($gross_amount, 2) }}</td>
                    @foreach ($loan_types as $loan_type)
                        <th class="whitespace-nowrap border-2 border-black px-4 text-right">{{ $records->where('loan_type_id', $loan_type->id)->sum('gross_amount') ? number_format($records->where('loan_type_id', $loan_type->id)->sum('gross_amount'), 2) : '' }}</th>
                    @endforeach
                    @foreach ($loan_types as $loan_type)
                        <th class="whitespace-nowrap border-2 border-black px-4 text-right">{{ $records->where('loan_type_id', $loan_type->id)->sum('net_amount') ? number_format($records->where('loan_type_id', $loan_type->id)->sum('net_amount'), 2) : '' }}</th>
                    @endforeach
                    <th class="whitespace-nowrap border-2 border-black px-4 text-right">{{ number_format($net_amount, 2) }}</th>
                </tr>
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'TOTAL LOAN RELEASED')">Print</x-filament::button>
    </div>
</div>
