<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title">
    <table class="doc-table">
        <thead>
            <tr>
                <th class="doc-table-header-cell">NO.</th>
                <th class="doc-table-header-cell">DATE</th>
                <th class="doc-table-header-cell">MEMBER NAME</th>
                <th class="doc-table-header-cell">PAYEE</th>
                <th class="doc-table-header-cell">ACCOUNT NAME</th>
                <th class="doc-table-header-cell">ACCOUNT NUMBER</th>
                <th class="doc-table-header-cell">REFERENCE #</th>
                <th class="doc-table-header-cell">DEBIT</th>
                <th class="doc-table-header-cell">CREDIT</th>
                <th class="doc-table-header-cell">RUNNING BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $balance = 0;
                $total_debit = 0;
                $total_credit = 0;
            @endphp
            @forelse ($this->table->getRecords() as $record)
                @php
                    $balance = $balance + ($record->debit ?? 0) - ($record->credit ?? 0);
                    $total_debit += $record->debit ?? 0;
                    $total_credit += $record->credit ?? 0;
                @endphp
                <tr>
                    <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                    <td class="doc-table-cell">
                        {{ $record->transaction_date->format('m/d/Y') }}
                    </td>
                    <td class="doc-table-cell">
                        {{ $record->member?->full_name }}
                    </td>
                    <td class="doc-table-cell">
                        {{ $record->payee }}
                    </td>
                    <td class="doc-table-cell">
                        {{ $record->account->name }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->account->number }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->debit, 2) }}</td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->credit, 2) }}</td>
                    <td class="doc-table-cell-center">{{ renumber_format($balance, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="doc-table-cell-center" colspan="10">No transactions today.</td>
                </tr>
            @endforelse
            <tr class="doc-table-row-total">
                <th class="doc-table-cell-center" colspan="7">GRAND TOTAL</th>
                <td class="doc-table-cell-center font-bold">
                    {{ renumber_format($total_debit, 2) }}
                </td>
                <td class="doc-table-cell-center font-bold">
                    {{ renumber_format($total_credit, 2) }}
                </td>
                <td class="doc-table-cell-center font-bold">{{ renumber_format($balance, 2) }}</td>
            </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
