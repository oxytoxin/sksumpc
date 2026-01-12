<x-filament-panels::page>
    <table class="doc-table">
        <thead>
            <tr>
                <th class="doc-table-header-cell">DATE</th>
                <th class="doc-table-header-cell">PARTICULAR</th>
                <th class="doc-table-header-cell">CTS/CV</th>
                <th class="doc-table-header-cell">DEBIT</th>
                <th class="doc-table-header-cell">CREDIT</th>
                <th class="doc-table-header-cell">TOTAL</th>
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
                    <td class="doc-table-cell-center">{{ $record->transaction_date->format('m/d/Y') }}</td>
                    <td class="doc-table-cell-center">
                        {{ $record->withdrawal ? 'WITHDRAWAL' : 'REPLENISHMENT' }}
                    </td>
                    <td class="doc-table-cell-center">{{ $record->reference_number }}</td>
                    <td class="doc-table-cell-right">
                        {{ renumber_format($record->withdrawal) }}
                    </td>
                    <td class="doc-table-cell-right">
                        {{ renumber_format($record->deposit) }}
                    </td>
                    <td class="doc-table-cell-right">{{ renumber_format($balance) }}</td>
                </tr>
            @empty
            <tr>
                <td colspan="6" class="doc-table-cell-center">No transactions today.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="doc-table-row-total">
                <td class="doc-table-cell-center font-bold" colspan="3">TOTAL</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->table->getRecords()->sum('withdrawal')) }}</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->table->getRecords()->sum('deposit')) }}</td>
                <td class="doc-table-cell-right"></td>
            </tr>
        </tfoot>
    </table>
    <x-filament-actions::modals />
</x-filament-panels::page>
