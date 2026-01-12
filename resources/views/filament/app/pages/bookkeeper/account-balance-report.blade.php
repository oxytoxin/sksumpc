<x-filament-panels::page>
    <div>{{ $this->form }}</div>
    <div wire:loading>
        <h3>Processing data...</h3>
    </div>
    <div class="w-full">
        <x-app.cashier.reports.report-layout>
            <table class="doc-table border border-black px-4 text-sm">
                <thead class="border border-black px-4">
                    <tr class="border border-black px-4">
                        <th colspan="5">CASH ON HAND</th>
                    </tr>
                    <tr class="border border-black px-4">
                        <th colspan="5">{{ $year }}</th>
                    </tr>
                    <tr>
                        <th class="doc-table-header-cell">DATE</th>
                        <th class="doc-table-header-cell">REFERENCE</th>
                        <th class="doc-table-header-cell">DEBIT</th>
                        <th class="doc-table-header-cell">CREDIT</th>
                        <th class="doc-table-header-cell">BALANCE</th>
                    </tr>
                </thead>
                @php
                    $balance = 0;
                @endphp
                <tbody>
                    @foreach (array_map(fn($month) => \Carbon\Carbon::create(null, $month)->format('F'), range(1, 12)) as $month)
                        <tr>
                            <td class="doc-table-cell font-semibold">{{ $month }} {{ $year }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->rice->firstWhere('month_name', $month)?->debit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">CRJ-RICE</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->rice->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->dormitory->firstWhere('month_name', $month)?->debit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">CRJ-DORMITORY</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->dormitory->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->laboratory->firstWhere('month_name', $month)?->debit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">CRJ-LABORATORY</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->laboratory->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->mso->firstWhere('month_name', $month)?->debit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">CRJ-MSO</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->mso->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->others->firstWhere('month_name', $month)?->debit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">CRJ-OTHERS</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->others->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->loans->firstWhere('month_name', $month)?->debit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">CRJ-LOANS</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->loans->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                        @php
                            $balance += $this->jev->firstWhere('month_name', $month)?->debit;
                            $balance -= $this->jev->firstWhere('month_name', $month)?->credit;
                        @endphp
                        <tr>
                            <td class="doc-table-cell font-semibold"></td>
                            <td class="doc-table-cell">JEV</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->jev->firstWhere('month_name', $month)?->debit) }}</td>
                            <td class="doc-table-cell-right">{{ renumber_format($this->jev->firstWhere('month_name', $month)?->credit) }}</td>
                            <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-app.cashier.reports.report-layout>
    </div>

</x-filament-panels::page>
