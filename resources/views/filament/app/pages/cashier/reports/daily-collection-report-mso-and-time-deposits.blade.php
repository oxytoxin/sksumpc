<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY COLLECTION REPORT OF MSO AND TIME DEPOSITS">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border border-black">NO.</th>
                    <th class="text-center border border-black">NAME</th>
                    <th class="text-center border border-black">REFERENCE NUMBER</th>
                    <th class="text-center border border-black">MSO</th>
                    <th class="text-center border border-black">LOVE GIFT</th>
                    <th class="text-center border border-black">IMPREST</th>
                    <th class="text-center border border-black">A/P</th>
                    <th class="text-center border border-black">TIME DEPOSIT</th>
                    <th class="text-center border border-black">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $no = 1;
                @endphp
                @forelse ($this->payments as $key => $payment_group)
                    @foreach ($payment_group as $payment)
                        @php
                            $row_total = 0;
                        @endphp
                        <tr>
                            <td class="text-center px-2 border border-black">{{ $no }}</td>
                            <td class="text-center px-2 border border-black">{{ $payment->member->alt_full_name }}</td>
                            <td class="text-center px-2 border border-black">{{ $payment->reference_number }}</td>
                            @if ($key == 'mso')
                                <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                                @php
                                    $row_total += $payment->amount;
                                    $total += $payment->amount;
                                @endphp
                            @else
                                <td class="text-center px-2 border border-black"></td>
                            @endif
                            @if ($key == 'love_gift')
                                <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                                @php
                                    $row_total += $payment->amount;
                                    $total += $payment->amount;
                                @endphp
                            @else
                                <td class="text-center px-2 border border-black"></td>
                            @endif
                            @if ($key == 'imprests')
                                <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                                @php
                                    $row_total += $payment->amount;
                                    $total += $payment->amount;
                                @endphp
                            @else
                                <td class="text-center px-2 border border-black"></td>
                            @endif
                            @if ($key == 'ap')
                                <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                                @php
                                    $row_total += $payment->amount;
                                    $total += $payment->amount;
                                @endphp
                            @else
                                <td class="text-center px-2 border border-black"></td>
                            @endif
                            @if ($key == 'time_deposits' || $key == 'time_deposits_withdrawal')
                                @if ($key == 'time_deposits_withdrawal')
                                    <td class="text-center px-2 border border-black">{{ number_format($payment->maturity_amount * -1, 2) }}</td>
                                    @php
                                        $row_total = $payment->maturity_amount * -1;
                                        $total += $payment->maturity_amount * -1;
                                    @endphp
                                @else
                                    <td class="text-center px-2 border border-black">{{ number_format($payment->amount, 2) }}</td>
                                    @php
                                        $row_total += $payment->amount;
                                        $total += $payment->amount;
                                    @endphp
                                @endif
                            @else
                                <td class="text-center px-2 border border-black"></td>
                            @endif
                            <td class="text-center px-2 border border-black">{{ number_format($row_total, 2) }}</td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                @empty
                    <tr>
                        <td colspan="9" class="text-center border border-black">No transactions today.</td>
                    </tr>
                @endforelse
                <tr>
                    <th class="text-center border border-black"></th>
                    <th class="text-center border border-black">TOTAL</th>
                    <th class="text-center border border-black"></th>
                    <th class="text-center border border-black">{{ number_format($this->payments['mso']->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($this->payments['love_gift']->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($this->payments['imprests']->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($this->payments['ap']->sum('amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($this->payments['time_deposits']->sum('amount') - $this->payments['time_deposits_withdrawal']->sum('maturity_amount'), 2) }}</th>
                    <th class="text-center border border-black">{{ number_format($total, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
