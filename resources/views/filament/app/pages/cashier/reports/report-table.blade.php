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
                        @if ($record instanceof App\Models\Saving)
                            {{ $record->savings_account?->number }}
                        @endif
                        @if ($record instanceof App\Models\Imprest)
                            {{ $record->member->imprest_account?->number }}
                        @endif
                        @if ($record instanceof App\Models\CapitalSubscriptionPayment)
                            {{ $record->member->capital_subscription_account?->number }}
                        @endif
                    </td>
                    <td class="doc-table-cell-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->deposit, 2) }}</td>
                    <td class="doc-table-cell-center">{{ renumber_format($record->withdrawal, 2) }}</td>
                    <td class="doc-table-cell-center">{{ $record->transaction_date?->format('m/d/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="doc-table-cell-center" colspan="7">No transactions today.</td>
                </tr>
            @endforelse
            <tr class="doc-table-row-total">
                <th class="doc-table-cell-center" colspan="4">GRAND TOTAL</th>
                <td class="doc-table-cell-center">{{ renumber_format($this->table->getRecords()->sum('deposit'), 2) }}</td>
                <td class="doc-table-cell-center">{{ renumber_format($this->table->getRecords()->sum('withdrawal'), 2) }}</td>
                <td class="doc-table-cell-center">{{ renumber_format($this->table->getRecords()->sum('amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
