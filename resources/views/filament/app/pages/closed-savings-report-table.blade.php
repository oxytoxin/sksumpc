<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title" :hasHeader="false">
    <table class="doc-table">
        <thead>
        <tr>
            <th class="doc-table-header-cell-center">NO.</th>
            <th class="doc-table-header-cell">ACCOUNT NUMBER</th>
            <th class="doc-table-header-cell">MPC CODE</th>
            <th class="doc-table-header-cell">MEMBER NAME</th>
            <th class="doc-table-header-cell">DATE CLOSED</th>
            <th class="doc-table-header-cell">REMARKS</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($this->table->getRecords() as $record)
            <tr>
                <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                <td class="doc-table-cell">{{ $record->number }}</td>
                <td class="doc-table-cell">{{ $record->member?->mpc_code }}</td>
                <td class="doc-table-cell">{{ $record->member?->full_name }}</td>
                <td class="doc-table-cell">{{ $record->closed_at?->format('m/d/Y') }}</td>
                <td class="doc-table-cell">{{ str($record->close_remarks)->words(7) ?? '—' }}</td>
            </tr>
        @empty
            <tr>
                <td class="doc-table-cell-center" colspan="6">No closed savings accounts found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
