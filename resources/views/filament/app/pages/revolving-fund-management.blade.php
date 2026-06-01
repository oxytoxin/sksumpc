<x-filament-panels::page>
    <div class="mx-auto max-w-7xl" x-data>
        <div class="p-4" x-ref="print">
            <x-app.cashier.reports.report-heading/>
            <h4 class="mt-4 text-center text-xl font-bold">REVOLVING FUND MANAGEMENT</h4>
            <p class="text-center font-bold">{{ (config('app.transaction_date') ?? today())->format('l, F d, Y') }}</p>
            <div class="my-4 text-sm print:text-[10pt]">
                <table class="doc-table">
                    <thead>
                    <tr>
                        <th class="doc-table-header-cell">DATE</th>
                        <th class="doc-table-header-cell">PARTICULAR</th>
                        <th class="doc-table-header-cell">MEMBER</th>
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
                            <td class="doc-table-cell-center">
                                {{ $record->withdrawable?->member?->full_name }}
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
                            <td colspan="7" class="doc-table-cell-center">No transactions today.</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr class="doc-table-row-total">
                        <td class="doc-table-cell-center font-bold" colspan="4">TOTAL</td>
                        <td class="doc-table-cell-right font-bold">{{ renumber_format($this->table->getRecords()->sum('withdrawal')) }}</td>
                        <td class="doc-table-cell-right font-bold">{{ renumber_format($this->table->getRecords()->sum('deposit')) }}</td>
                        <td class="doc-table-cell-right"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="flex justify-end gap-4 p-4">
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, '')">
                Print
            </x-filament::button>
        </div>
    </div>
    <x-filament-actions::modals/>
</x-filament-panels::page>
