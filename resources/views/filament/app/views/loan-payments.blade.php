<table class="doc-table">
    <thead>
        <tr>
            <th class="doc-table-header-cell">DATE</th>
            <th class="doc-table-header-cell">REF#</th>
            <th class="doc-table-header-cell">DEBIT</th>
            <th class="doc-table-header-cell">CREDIT</th>
            <th class="doc-table-header-cell">REMARKS</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($loan->payments as $payment)
            <tr>
                <td class="doc-table-cell">
                    {{ $payment->transaction_date->format('m/d/Y') }}
                </td>
                <td class="doc-table-cell">
                    {{ $payment->reference_number }}
                </td>
                <td class="doc-table-cell"></td>
                <td class="doc-table-cell">
                    {{ Filament\Support\format_money($payment->amount, 'PHP') }}
                </td>
                <td class="doc-table-cell">{{ $payment->remarks }}</td>
            </tr>
        @empty
            <tr>
                <td class="doc-table-cell" colspan="6">No payments made.</td>
            </tr>
        @endforelse
    </tbody>
</table>
