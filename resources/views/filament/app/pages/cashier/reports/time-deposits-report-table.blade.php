<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title">
    <table class="doc-table">
        <thead>
            <tr>
                <th class="doc-table-header-cell">NO.</th>
                <th class="doc-table-header-cell">MEMBER NAME</th>
                <th class="doc-table-header-cell">ACCOUNT NUMBER</th>
                <th class="doc-table-header-cell">REFERENCE #</th>
                <th class="doc-table-header-cell">DEPOSIT</th>
                <th class="doc-table-header-cell">WITHDRAWAL</th>
                <th class="doc-table-header-cell">DATE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($this->table->getRecords() as $record)
                <tr>
                    <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                    <td class="doc-table-cell-center">
                        {{ $record->member->full_name }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->account->number }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ renumber_format($record->credit, 2) }}</td>
                    <td class="doc-table-cell-center">
                        {{ renumber_format($record->debit, 2) }}</td>
                    <td class="doc-table-cell-center">
                        {{ $record->transaction_date?->format('m/d/Y') }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td class="doc-table-cell-center" colspan="5">No transactions today.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
