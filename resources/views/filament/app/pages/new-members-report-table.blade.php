<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title" :hasHeader="false">
    <table class="doc-table">
        <thead>
        <tr>
            <th class="doc-table-header-cell-center">NO.</th>
            <th class="doc-table-header-cell">DATE OF MEMBERSHIP</th>
            <th class="doc-table-header-cell">NAME OF MEMBERS</th>
            <th class="doc-table-header-cell">BOD RESOLUTION</th>
            <th class="doc-table-header-cell">INITIAL PAID UP CAPITAL</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($this->table->getRecords() as $record)
            <tr>
                <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                <td class="doc-table-cell">{{ $record->membership_date?->format('m/d/Y') }}</td>
                <td class="doc-table-cell">{{ $record->full_name }}</td>
                <td class="doc-table-cell">{{ $record->membership_acceptance?->bod_resolution }}</td>
                <td class="doc-table-cell text-right">{{ $record->initial_capital_subscription?->initial_amount_paid ? number_format($record->initial_capital_subscription->initial_amount_paid, 2) : '' }}</td>
            </tr>
        @empty
            <tr>
                <td class="doc-table-cell-center" colspan="5">No new members found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
