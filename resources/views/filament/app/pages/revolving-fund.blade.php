<x-filament-panels::page>
    <table>
        <thead>
        <tr>
            <th class="border border-black">DATE</th>
            <th class="border border-black">PARTICULAR</th>
            <th class="border border-black">CTS/CV</th>
            <th class="border border-black px-4 text-right">DEBIT</th>
            <th class="border border-black px-4 text-right">CREDIT</th>
            <th class="border border-black px-4 text-right">TOTAL</th>
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
                <td class="border border-black text-center">{{ $record->transaction_datetime }}</td>
                <td class="border border-black text-center">
                    {{ $record->withdrawal ? 'WITHDRAWAL' : 'REPLENISHMENT' }}
                </td>
                <td class="border border-black text-center">{{ $record->reference_number }}</td>
                <td class="border border-black text-right px-4">
                    {{ renumber_format($record->withdrawal) }}
                </td>
                <td class="border border-black text-right px-4">
                    {{ renumber_format($record->deposit) }}
                </td>
                <td class="border border-black px-4 text-right">{{ renumber_format($balance) }}</td>
            </tr>
        @empty
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td class="border border-black text-center font-bold" colspan="3">TOTAL</td>
            <td class="border border-black px-4 text-right">{{ renumber_format($this->table->getRecords()->sum('withdrawal')) }}</td>
            <td class="border border-black px-4 text-right">{{ renumber_format($this->table->getRecords()->sum('deposit')) }}</td>
            <td class="border border-black px-4 text-right"></td>
        </tr>
        </tfoot>
    </table>
    <x-filament-actions::modals/>
</x-filament-panels::page>
