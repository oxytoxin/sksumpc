<x-filament-panels::page>
    <div>
        <x-app.cashier.reports.report-layout :title="'Loan History - ' . $member->full_name">
            <table class="w-full border border-black text-xs print:text-[7pt]">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">#</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">Ref No.</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">Loan Type</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">Terms</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-right text-xs print:text-[7pt]">Gross Amount</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-right text-xs print:text-[7pt]">Interest</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-right text-xs print:text-[7pt]">Deductions</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-right text-xs print:text-[7pt]">Net Amount</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-right text-xs print:text-[7pt]">Monthly Payment</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-right text-xs print:text-[7pt]">Outstanding Balance</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">Status</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">Transaction Date</th>
                        <th class="whitespace-nowrap border border-black px-1 py-0.5 text-left text-xs print:text-[7pt]">Release Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $loans = $member->loans()->with('loan_type')->orderBy('transaction_date', 'desc')->get();
                        $totalGross = 0;
                        $totalInterest = 0;
                        $totalDeductions = 0;
                        $totalNet = 0;
                        $totalOutstanding = 0;
                    @endphp
                    @forelse ($loans as $i => $loan)
                        @php
                            $totalGross += $loan->gross_amount;
                            $totalInterest += $loan->interest;
                            $totalDeductions += $loan->deductions_amount;
                            $totalNet += $loan->net_amount;
                            $totalOutstanding += max(0, $loan->outstanding_balance);
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5">{{ $loop->iteration }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5">{{ $loan->reference_number }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5">{{ $loan->loan_type?->name }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-center">{{ $loan->number_of_terms }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($loan->gross_amount, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($loan->interest, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($loan->deductions_amount, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($loan->net_amount, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($loan->monthly_payment, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-center">
                                @if(!$loan->posted)
                                    <span class="text-yellow-600">Pending</span>
                                @elseif($loan->outstanding_balance <= 0)
                                    <span class="text-green-600">Paid</span>
                                @else
                                    <span class="text-blue-600">On-going</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5">{{ $loan->transaction_date?->format('m/d/Y') }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5">{{ $loan->release_date?->format('m/d/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="whitespace-nowrap border border-black px-1 py-4 text-center">No loan records found.</td>
                        </tr>
                    @endforelse
                    @if($loans->count() > 0)
                        <tr class="font-bold bg-gray-100">
                            <td class="whitespace-nowrap border border-black px-1 py-0.5" colspan="4">TOTAL</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($totalGross, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($totalInterest, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($totalDeductions, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($totalNet, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5"></td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5 text-right">{{ number_format($totalOutstanding, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-1 py-0.5" colspan="2"></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </x-app.cashier.reports.report-layout>
    </div>
</x-filament-panels::page>
