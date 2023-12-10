<x-filament-panels::page>
    <div>
        {{ $this->form }}
    </div>
    <x-app.cashier.reports.report-layout :signatories="$signatories">
        <div>
            <h3 class="text-center font-bold text-lg">MONTHLY SUMMARY OF MEMBER'S SAVINGS DEPOSIT AND WITHDRAWAL</h3>
            <h3 class="text-center font-bold uppercase">FOR THE MONTH OF {{ oxy_get_month_range()[$data['month']] }} {{ $data['year'] }}</h3>
        </div>
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th class="text-center border border-black">NO.</th>
                    <th class="text-center border border-black">ACCOUNT NAME</th>
                    <th class="text-center border border-black">ACCOUNT NUMBER</th>
                    <th class="text-center border border-black">DEPOSIT</th>
                    <th class="text-center border border-black">WITHDRAWAL</th>
                    <th class="text-center border border-black">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @forelse ($this->savings as $saving)
                    @php
                        $total += $saving->amount;
                    @endphp
                    <tr>
                        <th class="text-center border border-black">{{ $loop->iteration }}</th>
                        <td class="text-center border border-black whitespace-nowrap px-2">{{ $saving->savings_account->name }}</td>
                        <td class="text-center border border-black whitespace-nowrap px-2">{{ $saving->savings_account->number }}</td>
                        <td class="text-center border border-black">{{ $saving->deposit ? number_format($saving->deposit, 2) : '' }}</td>
                        <td class="text-center border border-black">{{ $saving->withdrawal ? number_format($saving->withdrawal, 2) : '' }}</td>
                        <td class="text-center border border-black">{{ number_format($total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center border border-black">No transactions today.</td>
                    </tr>
                @endforelse
                <tr>
                    <th colspan="3" class="text-left border border-black">TOTAL</th>
                    <td class="text-center border border-black whitespace-nowrap px-2">{{ number_format($this->savings->sum('deposit'), 2) }}</td>
                    <td class="text-center border border-black whitespace-nowrap px-2">{{ number_format($this->savings->sum('withdrawal'), 2) }}</td>
                    <td class="text-center border border-black">{{ number_format($total, 2) }}</td>
                    <td clas </tbody>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
