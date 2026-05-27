<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title">
    <table class="doc-table">
        <thead>
        <tr>
            <th class="doc-table-header-cell-center">NO.</th>
            <th class="doc-table-header-cell-center">DATE</th>
            <th class="doc-table-header-cell-center">REFERENCE NUMBER</th>
            <th class="doc-table-header-cell-center">BILLING TYPE</th>
            <th class="doc-table-header-cell-center">AMOUNT</th>
            <th class="doc-table-header-cell-center">RUNNING TOTAL</th>
        </tr>
        </thead>
        <tbody>
        @php
            $runningTotal = 0;
            $totalAmount = 0;
        @endphp
        @forelse ($this->table->getRecords() as $record)
            @php
                $runningTotal += floatval($record->amount);
                $totalAmount += floatval($record->amount);
            @endphp
            <tr>
                <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                <td class="doc-table-cell-center">
                    {{ $record->date->format('m/d/Y') }}
                </td>
                <td class="doc-table-cell-center">
                    {{ $record->reference_number }}
                </td>
                <td class="doc-table-cell-center">
                    {{ $record->billing_type }}
                </td>
                <td class="doc-table-cell-center">{{ renumber_format($record->amount, 2) }}</td>
                <td class="doc-table-cell-center">{{ renumber_format($runningTotal, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td class="doc-table-cell-center" colspan="6">No billing payments found.</td>
            </tr>
        @endforelse
        <tr class="doc-table-row-total">
            <th class="doc-table-cell-center" colspan="5">GRAND TOTAL</th>
            <td class="doc-table-cell-center font-bold">{{ renumber_format($totalAmount, 2) }}</td>
        </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
