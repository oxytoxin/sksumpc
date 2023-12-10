<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY COLLECTION REPORT OF LADIES DORMITORY">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border border-black">NO.</th>
                    <th class="text-center border border-black">NAME</th>
                    <th class="text-center border border-black">REFERENCE NUMBER</th>
                    <th class="text-center border border-black">RESERVATION</th>
                    <th class="text-center border border-black">RENTALS</th>
                    <th class="text-center border border-black">OTHERS-ELECTRIC</th>
                    <th class="text-center border border-black">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $no = 1;
                @endphp
                @forelse ($this->payments as $payment)
                    <tr>
                        <td class="text-center px-2 border border-black">{{ $no }}</td>
                        <td class="text-center px-2 border border-black">{{ $payment->member->alt_full_name }}</td>
                        <td class="text-center px-2 border border-black">{{ $payment->reference_number }}</td>
                        @if ($payment->cash_collectible_id == 3)
                            <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                        @else
                            <td class="text-center px-2 border border-black"></td>
                        @endif
                        @if ($payment->cash_collectible_id == 4)
                            <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                        @else
                            <td class="text-center px-2 border border-black"></td>
                        @endif
                        @if ($payment->cash_collectible_id == 5)
                            <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                        @else
                            <td class="text-center px-2 border border-black"></td>
                        @endif
                        <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @php
                        $total += $payment->amount;
                        $no++;
                    @endphp
                @empty
                    <tr>
                        <td colspan="7" class="text-center border border-black">No transactions today.</td>
                    </tr>
                @endforelse
                <tr>
                    <th class="text-center border border-black"></th>
                    <th class="text-center border border-black">TOTAL</th>
                    <th class="text-center border border-black"></th>
                    <th class="text-center border border-black">{{ number_format($this->payments->where('cash_collectible_id', 3)->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($this->payments->where('cash_collectible_id', 4)->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($this->payments->where('cash_collectible_id', 5)->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($total, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
