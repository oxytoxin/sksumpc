<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY SUMMARY OF MEMBER'S TIME DEPOSITS DEPOSIT AND WITHDRAWAL">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border border-black">NO.</th>
                    <th class="text-center border border-black">ACCOUNT NAME</th>
                    <th class="text-center border border-black">DEPOSIT</th>
                    <th class="text-center border border-black">WITHDRAWAL</th>
                    <th class="text-center border border-black">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $number = 0;
                @endphp
                @forelse ($this->time_deposits as $time_deposit)
                    @if ($this->time_deposit->withdrawal_date)
                        @php
                            $total += $time_deposit->amount;
                            $number++;
                        @endphp
                        <tr>
                            <th class="text-center border border-black">{{ $number }}</th>
                            <td class="text-center border border-black whitespace-nowrap px-2">{{ $time_deposit->member->full_name }}</td>
                            <td class="text-center border border-black">{{ number_format($time_deposit->amount, 2) }}</td>
                            <td class="text-center border border-black"></td>
                            <td class="text-center border border-black">{{ number_format($total, 2) }}</td>
                        </tr>
                        @php
                            $total -= $time_deposit->maturity_amount;
                            $number++;
                        @endphp
                        <tr>
                            <th class="text-center border border-black">{{ $number }}</th>
                            <td class="text-center border border-black whitespace-nowrap px-2">{{ $time_deposit->member->full_name }}</td>
                            <td class="text-center border border-black"></td>
                            <td class="text-center border border-black">{{ number_format($time_deposit->maturity_amount, 2) }}</td>
                            <td class="text-center border border-black">{{ number_format($total, 2) }}</td>
                        </tr>
                    @else
                        @php
                            $total += $time_deposit->amount;
                            $number++;
                        @endphp
                        <tr>
                            <th class="text-center border border-black">{{ $number }}</th>
                            <td class="text-center border border-black whitespace-nowrap px-2">{{ $time_deposit->member->full_name }}</td>
                            <td class="text-center border border-black">{{ number_format($time_deposit->amount, 2) }}</td>
                            <td class="text-center border border-black"></td>
                            <td class="text-center border border-black">{{ number_format($total, 2) }}</td>
                        </tr>
                    @endif

                @empty
                    <tr>
                        <td colspan="5" class="text-center border border-black">No transactions today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-app.cashier.reports.report-layout>

</x-filament-panels::page>
