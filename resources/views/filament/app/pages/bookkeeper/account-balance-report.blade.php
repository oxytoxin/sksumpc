<x-filament-panels::page>
    <div>
        {{ $this->form }}
    </div>
    <div wire:loading>
        <h3>Processing data...</h3>
    </div>
    <div class="w-full">
        <x-app.cashier.reports.report-layout>
            <table class="w-full text-sm border border-black px-4">
                <thead class="border border-black px-4">
                <tr class="border border-black px-4">
                    <th colspan="5">CASH ON HAND</th>
                </tr>
                <tr class="border border-black px-4">
                    <th colspan="5">{{ $year }}</th>
                </tr>
                <tr>
                    <th class="border border-black px-4">DATE</th>
                    <th class="border border-black px-4">REFERENCE</th>
                    <th class="border border-black px-4">DEBIT</th>
                    <th class="border border-black px-4">CREDIT</th>
                    <th class="border border-black px-4">BALANCE</th>
                </tr>
                </thead>
                @php
                    $balance = 0;
                @endphp
                <tbody>
                @foreach(array_map(fn($month) => \Carbon\Carbon::create(null, $month)->format('F'), range(1, 12)) as $month)
                    <tr>
                        <td class="font-semibold border border-black px-4">{{ $month }} {{ $year }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->rice->firstWhere('month_name', $month)?->debit;
                    @endphp
                    <tr>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4">CRJ-RICE</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->rice->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->dormitory->firstWhere('month_name', $month)?->debit;
                    @endphp
                    <tr>
                        <td class="font-semibold border border-black px-4"></td>
                        <td class="border border-black px-4">CRJ-DORMITORY</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->dormitory->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->laboratory->firstWhere('month_name', $month)?->debit;
                    @endphp
                    <tr>
                        <td class="font-semibold border border-black px-4"></td>
                        <td class="border border-black px-4">CRJ-LABORATORY</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->laboratory->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->mso->firstWhere('month_name', $month)?->debit;
                    @endphp
                    <tr>
                        <td class="font-semibold border border-black px-4"></td>
                        <td class="border border-black px-4">CRJ-MSO</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->mso->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->others->firstWhere('month_name', $month)?->debit;
                    @endphp
                    <tr>
                        <td class="font-semibold border border-black px-4"></td>
                        <td class="border border-black px-4">CRJ-OTHERS</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->others->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->loans->firstWhere('month_name', $month)?->debit;
                    @endphp
                    <tr>
                        <td class="font-semibold border border-black px-4"></td>
                        <td class="border border-black px-4">CRJ-LOANS</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->loans->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                    @php
                        $balance += $this->jev->firstWhere('month_name', $month)?->debit;
                        $balance -= $this->jev->firstWhere('month_name', $month)?->credit;
                    @endphp
                    <tr>
                        <td class="font-semibold border border-black px-4"></td>
                        <td class="border border-black px-4">JEV</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->jev->firstWhere('month_name', $month)?->debit) }}</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($this->jev->firstWhere('month_name', $month)?->credit) }}</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </x-app.cashier.reports.report-layout>
    </div>

</x-filament-panels::page>
