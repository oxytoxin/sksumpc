@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>
    @php
        $time_deposits = auth()
            ->user()
            ->cashier_time_deposits()
            ->with('member')
            ->whereDate('transaction_date', today())
            ->orWhereDate('withdrawal_date', today())
            ->get();
    @endphp

    <x-app.cashier.reports.report-layout title="DAILY SUMMARY OF MEMBER'S TIME DEPOSITS DEPOSIT AND WITHDRAWAL">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border-2 border-black">NO.</th>
                    <th class="text-center border-2 border-black">ACCOUNT NAME</th>
                    <th class="text-center border-2 border-black">DEPOSIT</th>
                    <th class="text-center border-2 border-black">WITHDRAWAL</th>
                    <th class="text-center border-2 border-black">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $number = 0;
                @endphp
                @forelse ($time_deposits as $time_deposit)
                    @if ($time_deposit->withdrawal_date)
                        @php
                            $total += $time_deposit->amount;
                            $number++;
                        @endphp
                        <tr>
                            <th class="text-center border-2 border-black">{{ $number }}</th>
                            <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $time_deposit->member->full_name }}</td>
                            <td class="text-center border-2 border-black">{{ format_money($time_deposit->amount, 'PHP') }}</td>
                            <td class="text-center border-2 border-black"></td>
                            <td class="text-center border-2 border-black">{{ format_money($total, 'PHP') }}</td>
                        </tr>
                        @php
                            $total -= $time_deposit->maturity_amount;
                            $number++;
                        @endphp
                        <tr>
                            <th class="text-center border-2 border-black">{{ $number }}</th>
                            <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $time_deposit->member->full_name }}</td>
                            <td class="text-center border-2 border-black"></td>
                            <td class="text-center border-2 border-black">{{ format_money($time_deposit->maturity_amount, 'PHP') }}</td>
                            <td class="text-center border-2 border-black">{{ format_money($total, 'PHP') }}</td>
                        </tr>
                    @else
                        @php
                            $total += $time_deposit->amount;
                            $number++;
                        @endphp
                        <tr>
                            <th class="text-center border-2 border-black">{{ $number }}</th>
                            <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $time_deposit->member->full_name }}</td>
                            <td class="text-center border-2 border-black">{{ format_money($time_deposit->amount, 'PHP') }}</td>
                            <td class="text-center border-2 border-black"></td>
                            <td class="text-center border-2 border-black">{{ format_money($total, 'PHP') }}</td>
                        </tr>
                    @endif

                @empty
                    <tr>
                        <td colspan="5" class="text-center border-2 border-black">No transactions today.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        <x-slot:signatories>
            <x-app.cashier.reports.signatories :signatories="$signatories" />
        </x-slot:signatories>
        <x-slot:buttons>
            <x-filament::button color="success" tag="a" href="{{ route('filament.app.resources.members.index') }}">Back to Membership Module</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Daily Summary Time Deposits')">Print</x-filament::button>
        </x-slot:buttons>
    </x-app.cashier.reports.report-layout>

</x-filament-panels::page>