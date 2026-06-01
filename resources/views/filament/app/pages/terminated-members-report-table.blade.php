<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title" :hasHeader="false">
    <table class="doc-table">
        <thead>
        <tr>
            <th class="doc-table-header-cell-center">NO.</th>
            <th class="doc-table-header-cell">DATE OF TERMINATION</th>
            <th class="doc-table-header-cell">NAME OF MEMBER</th>
            <th class="doc-table-header-cell">BOD RESOLUTION</th>
            <th class="doc-table-header-cell">REFERENCE (JEV OR DV NO.)</th>
            <th class="doc-table-header-cell">TOTAL CAPITAL AMOUNT CLOSED</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($this->table->getRecords() as $record)
            <tr>
                <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                <td class="doc-table-cell">{{ $record->terminated_at?->format('m/d/Y') }}</td>
                <td class="doc-table-cell">{{ $record->full_name }}</td>
                <td class="doc-table-cell">{{ $record->membership_termination?->bod_resolution }}</td>
                <td class="doc-table-cell">{{ $record->membership_termination?->termination_voucher_number }}</td>
                <td class="doc-table-cell text-right">{{ $record->membership_termination?->capital_amount_closed ? number_format($record->membership_termination->capital_amount_closed, 2) : '' }}</td>
            </tr>
        @empty
            <tr>
                <td class="doc-table-cell-center" colspan="6">No terminated members found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
