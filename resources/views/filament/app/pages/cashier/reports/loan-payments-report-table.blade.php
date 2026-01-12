<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title">
    <table class="doc-table">
        <thead>
            <tr>
                <th class="doc-table-header-cell">NO.</th>
                <th class="doc-table-header-cell">MEMBER NAME</th>
                <th class="doc-table-header-cell">ACCOUNT #</th>
                <th class="doc-table-header-cell">LOAN TYPE</th>
                <th class="doc-table-header-cell">REFERENCE #</th>
                <th class="doc-table-header-cell">AMOUNT</th>
                <th class="doc-table-header-cell">PRINCIPAL</th>
                <th class="doc-table-header-cell">INTEREST</th>
                <th class="doc-table-header-cell">SURCHARGE</th>
                <th class="doc-table-header-cell">DATE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $amount = 0;
                $principal_payment = 0;
                $interest_payment = 0;
                $surcharge_payment = 0;
            @endphp
            @forelse ($this->table->getRecords() as $record)
                @php
                    $amount += $record->amount;
                    $principal_payment += $record->principal_payment;
                    $interest_payment += $record->interest_payment;
                    $surcharge_payment += $record->surcharge_payment;
                @endphp
                <tr>
                    <th class="doc-table-cell-center">{{ $loop->iteration }}</th>
                    <td class="doc-table-cell-center">
                        {{ $record->member->full_name }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->loan->loan_account->number }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->loan->loan_type->name }}
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->amount, 2) }}</td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->principal_payment, 2) }}</td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->interest_payment, 2) }}</td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->surcharge_payment, 2) }}</td>
                    <td class="doc-table-cell-center">{{ $record->transaction_date?->format('m/d/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="doc-table-cell-center" colspan="5">No transactions today.</td>
                </tr>
            @endforelse
            <tr class="doc-table-row-total">
                <th class="doc-table-cell-center" colspan="5">GRAND TOTAL</th>
                <td class="doc-table-cell-center font-bold">{{ renumber_format($amount, 2) }}</td>
                <td class="doc-table-cell-center font-bold">{{ renumber_format($principal_payment, 2) }}</td>
                <td class="doc-table-cell-center font-bold">{{ renumber_format($interest_payment, 2) }}</td>
                <td class="doc-table-cell-center font-bold">{{ renumber_format($surcharge_payment, 2) }}</td>
                <td class="doc-table-cell-center"></td>
            </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
