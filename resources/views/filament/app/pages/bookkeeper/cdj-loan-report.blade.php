<x-filament-panels::page>
    {{ $this->form }}
    <x-app.cashier.reports.report-layout>
        <div class="text-center">
            <h2 class="font-bold">CASH DISBURSEMENT JOURNAL</h2>
            <h3 class="font-bold">{{ oxy_get_month_range()[$data['month']] }} {{ $data['year'] }}</h3>
        </div>
        <div class="overflow-auto p-4">
            <table class="doc-table print:text-[10pt]">
                <thead>
                    <tr>
                        <th rowspan="2" colspan="3" class="whitespace-nowrap doc-table-header-cell">CDJ SL- LOANS RECEIVABLES- {{ oxy_get_month_range()[$data['month']] }} {{ $data['year'] }}</th>
                        <th colspan="{{ $this->loan_types->count() * 2 + 2 }}" class="whitespace-nowrap doc-table-header-cell">LOANS</th>
                        <th colspan="{{ $this->loan_types->count() * 2 + 2 }}" class="whitespace-nowrap doc-table-header-cell">INTEREST</th>
                    </tr>
                    <tr>
                        @foreach ($this->loan_types as $loan_type)
                            <th colspan="2" class="whitespace-nowrap doc-table-header-cell">{{ $loan_type->code }}</th>
                        @endforeach
                        <th class="whitespace-nowrap doc-table-header-cell">TOTAL DEBIT</th>
                        <th class="whitespace-nowrap doc-table-header-cell">TOTAL CREDIT</th>
                        @foreach ($this->loan_types as $loan_type)
                            <th colspan="2" class="whitespace-nowrap doc-table-header-cell">{{ $loan_type->code }}</th>
                        @endforeach
                        <th class="whitespace-nowrap doc-table-header-cell">TOTAL DEBIT</th>
                        <th class="whitespace-nowrap doc-table-header-cell">TOTAL CREDIT</th>
                    </tr>
                    <tr>
                        <th class="whitespace-nowrap doc-table-header-cell">DATE</th>
                        <th class="whitespace-nowrap doc-table-header-cell">NAME</th>
                        <th class="whitespace-nowrap doc-table-header-cell">REFERENCE NUMBER</th>
                        @foreach ($this->loan_types as $loan_type)
                            <th class="whitespace-nowrap doc-table-header-cell">DEBIT</th>
                            <th class="whitespace-nowrap doc-table-header-cell">CREDIT</th>
                        @endforeach
                        <th class="whitespace-nowrap doc-table-header-cell"></th>
                        <th class="whitespace-nowrap doc-table-header-cell"></th>
                        @foreach ($this->loan_types as $loan_type)
                            <th class="whitespace-nowrap doc-table-header-cell">DEBIT</th>
                            <th class="whitespace-nowrap doc-table-header-cell">CREDIT</th>
                        @endforeach
                        <th class="whitespace-nowrap doc-table-header-cell"></th>
                        <th class="whitespace-nowrap doc-table-header-cell"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grand_total_loan_debit = 0;
                        $grand_total_loan_credit = 0;
                        $grand_total_interest_debit = 0;
                        $grand_total_interest_credit = 0;
                    @endphp
                    @forelse ($this->receivables as $receivable)
                        @php
                            $buyout = collect($receivable->deductions)
                                ->where('code', 'buy_out')
                                ->sum('amount');
                            $total_loan_debit = 0;
                            $total_loan_credit = 0;
                            $total_interest_debit = 0;
                            $total_interest_credit = 0;
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap doc-table-cell">{{ $receivable->transaction_date->format('m/d/Y') }}</td>
                            <td class="whitespace-nowrap doc-table-cell">{{ $receivable->member->alt_full_name }}</td>
                            <td class="whitespace-nowrap doc-table-cell">{{ $receivable->reference_number }}</td>
                            @foreach ($this->loan_types as $loan_type)
                                <td class="whitespace-nowrap doc-table-cell-right">
                                    {{ $receivable->loan_type_id == $loan_type->id ? number_format($receivable->gross_amount, 2) : '' }}
                                </td>
                                <td class="whitespace-nowrap doc-table-cell-right">
                                    {{ $receivable->loan_type_id == $loan_type->id && $buyout > 0 ? number_format($buyout, 2) : '' }}
                                </td>
                            @endforeach
                            @php
                                $total_loan_debit += $receivable->gross_amount;
                                $total_loan_credit += $buyout;
                            @endphp
                            <td class="whitespace-nowrap doc-table-cell">{{ number_format($total_loan_debit, 2) }}</td>
                            <td class="whitespace-nowrap doc-table-cell">{{ number_format($total_loan_credit, 2) }}</td>
                            @php
                                $grand_total_loan_debit += $total_loan_debit;
                                $grand_total_loan_credit += $total_loan_credit;
                            @endphp
                            @foreach ($this->loan_types as $loan_type)
                                <td class="whitespace-nowrap doc-table-cell-right"></td>
                                <td class="whitespace-nowrap doc-table-cell-right">
                                    {{ $receivable->loan_type_id == $loan_type->id ? number_format($receivable->interest, 2) : '' }}
                                </td>
                            @endforeach
                            @php
                                $total_interest_credit += $receivable->interest;
                            @endphp
                            <td class="whitespace-nowrap doc-table-cell"></td>
                            <td class="whitespace-nowrap doc-table-cell">{{ $total_interest_credit ? number_format($total_interest_credit, 2) : '' }}</td>
                            @php
                                $grand_total_interest_debit += $total_interest_debit;
                                $grand_total_interest_credit += $total_interest_credit;
                            @endphp
                        </tr>
                    @empty
                    @endforelse
                    {{-- <tr>
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
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
