<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title" :hasHeader="false">
    <table class="doc-table">
        <thead>
        <tr>
            <th class="doc-table-header-cell-center">NO.</th>
            <th class="doc-table-header-cell">MPC CODE</th>
            <th class="doc-table-header-cell">MEMBER NAME</th>
            <th class="doc-table-header-cell">MEMBERSHIP DATE</th>
            <th class="doc-table-header-cell">MEMBER TYPE</th>
            <th class="doc-table-header-cell">DIVISION</th>
            <th class="doc-table-header-cell">GENDER</th>
            <th class="doc-table-header-cell">CIVIL STATUS</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($this->table->getRecords() as $record)
            <tr>
                <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                <td class="doc-table-cell">{{ $record->mpc_code }}</td>
                <td class="doc-table-cell">{{ $record->full_name }}</td>
                <td class="doc-table-cell">{{ $record->membership_date?->format('m/d/Y') }}</td>
                <td class="doc-table-cell">{{ $record->member_type?->name }}</td>
                <td class="doc-table-cell">{{ $record->division?->name }}</td>
                <td class="doc-table-cell">{{ $record->gender?->name }}</td>
                <td class="doc-table-cell">{{ $record->civil_status?->name }}</td>
            </tr>
        @empty
            <tr>
                <td class="doc-table-cell-center" colspan="8">No new members found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
