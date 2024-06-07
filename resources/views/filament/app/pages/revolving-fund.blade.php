<x-filament-panels::page>
    <table>
        <thead>
            <tr>
                <th class="border border-black"></th>
                <th class="border border-black">REF</th>
                <th class="border border-black px-4 text-right">WITHDRAW</th>
                <th class="border border-black px-4 text-right">CASH IN</th>
                <th class="border border-black px-4 text-right">BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $balance = 0;
            @endphp
            @forelse ($this->table->getRecords() as $record)
                @php
                    $balance += $record->deposit;
                    $balance -= $record->withdrawal;
                @endphp
                <tr>
                    <td class="border border-black text-center">
                        @if ($record->deposit)
                            CI
                        @else
                            W
                        @endif
                    </td>
                    <td class="border border-black text-center">{{ $record->reference_number }}</td>
                    <td class="border border-black px-4 text-right">{{ renumber_format($record->withdrawal) }}</td>
                    <td class="border border-black px-4 text-right">{{ renumber_format($record->deposit) }}</td>
                    <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td class="border border-black text-center"></td>
                <td class="border border-black text-center font-bold">TOTAL</td>
                <td class="border border-black px-4 text-right">{{ renumber_format($this->table->getRecords()->sum('withdrawal')) }}</td>
                <td class="border border-black px-4 text-right">{{ renumber_format($this->table->getRecords()->sum('deposit')) }}</td>
                <td class="border border-black px-4 text-right"></td>
            </tr>
        </tfoot>
    </table>
    <x-filament-actions::modals />
</x-filament-panels::page>
