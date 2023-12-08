<x-filament-panels::page>
    {{ $this->form }}
    <x-app.cashier.reports.report-layout>
        <div class="text-center">
            <h2 class="font-bold">CASH DISBURSEMENT JOURNAL</h2>
            <h3 class="font-bold">{{ oxy_get_month_range()[$data['month']] }} {{ $data['year'] }}</h3>
        </div>
        <div class="p-4 overflow-auto ">
            <table class="print:text-[10pt] w-full">
                <thead>
                    <tr>
                        <th rowspan="2" colspan="3" class="text-center border border-black whitespace-nowrap p-2">CDJ SL- LOANS RECEIVABLES- {{ oxy_get_month_range()[$data['month']] }} {{ $data['year'] }}</th>
                        <th colspan="{{ $this->loan_types->count() * 2 + 2 }}" class="text-center border border-black whitespace-nowrap p-1">LOANS</th>
                        <th colspan="{{ $this->loan_types->count() * 2 + 2 }}" class="text-center border border-black whitespace-nowrap p-1">INTEREST</th>
                    </tr>
                    <tr>
                        @foreach ($this->loan_types as $loan_type)
                            <th colspan="2" class="text-center border border-black whitespace-nowrap px-4">{{ $loan_type->code }}</th>
                        @endforeach
                        <th class="text-center border border-black whitespace-nowrap px-4">TOTAL DEBIT</th>
                        <th class="text-center border border-black whitespace-nowrap px-4">TOTAL CREDIT</th>
                        @foreach ($this->loan_types as $loan_type)
                            <th colspan="2" class="text-center border border-black whitespace-nowrap px-4">{{ $loan_type->code }}</th>
                        @endforeach
                        <th class="text-center border border-black whitespace-nowrap px-4">TOTAL DEBIT</th>
                        <th class="text-center border border-black whitespace-nowrap px-4">TOTAL CREDIT</th>
                    </tr>
                    <tr>
                        <th class="text-left border border-black whitespace-nowrap px-4">DATE</th>
                        <th class="text-left border border-black whitespace-nowrap px-4">NAME</th>
                        <th class="text-left border border-black whitespace-nowrap px-4">REFERENCE NUMBER</th>
                        @foreach ($this->loan_types as $loan_type)
                            <th class="text-center border border-black whitespace-nowrap px-4">DEBIT</th>
                            <th class="text-center border border-black whitespace-nowrap px-4">CREDIT</th>
                        @endforeach
                        <th class="text-center border border-black whitespace-nowrap px-4"></th>
                        <th class="text-center border border-black whitespace-nowrap px-4"></th>
                        @foreach ($this->loan_types as $loan_type)
                            <th class="text-center border border-black whitespace-nowrap px-4">DEBIT</th>
                            <th class="text-center border border-black whitespace-nowrap px-4">CREDIT</th>
                        @endforeach
                        <th class="text-center border border-black whitespace-nowrap px-4"></th>
                        <th class="text-center border border-black whitespace-nowrap px-4"></th>
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
                            <td class="text-left px-4 border border-black whitespace-nowrap">{{ $receivable->transaction_date->format('m/d/Y') }}</td>
                            <td class="text-left px-4 border border-black whitespace-nowrap">{{ $receivable->member->alt_full_name }}</td>
                            <td class="text-left px-4 border border-black whitespace-nowrap">{{ $receivable->reference_number }}</td>
                            @foreach ($this->loan_types as $loan_type)
                                <td class="text-right px-4 border border-black whitespace-nowrap">{{ $receivable->loan_type_id == $loan_type->id ? number_format($receivable->gross_amount, 2) : '' }}</td>
                                <td class="text-right px-4 border border-black whitespace-nowrap">{{ $receivable->loan_type_id == $loan_type->id && $buyout > 0 ? number_format($buyout, 2) : '' }}</td>
                            @endforeach
                            @php
                                $total_loan_debit += $receivable->gross_amount;
                                $total_loan_credit += $buyout;
                            @endphp
                            <td class="text-left px-4 border border-black whitespace-nowrap">{{ number_format($total_loan_debit, 2) }}</td>
                            <td class="text-left px-4 border border-black whitespace-nowrap">{{ number_format($total_loan_credit, 2) }}</td>
                            @php
                                $grand_total_loan_debit += $total_loan_debit;
                                $grand_total_loan_credit += $total_loan_credit;
                            @endphp
                            @foreach ($this->loan_types as $loan_type)
                                <td class="text-right px-4 border border-black whitespace-nowrap"></td>
                                <td class="text-right px-4 border border-black whitespace-nowrap">{{ $receivable->loan_type_id == $loan_type->id ? number_format($receivable->interest, 2) : '' }}</td>
                            @endforeach
                            @php
                                $total_interest_credit += $receivable->interest;
                            @endphp
                            <td class="text-left px-4 border border-black whitespace-nowrap"></td>
                            <td class="text-left px-4 border border-black whitespace-nowrap">{{ $total_interest_credit ? number_format($total_interest_credit, 2) : '' }}</td>
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
