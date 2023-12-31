<table>
    <thead>
        <tr class="border-2 border-black">
            <th class="border-2 border-black text-left">DATE</th>
            <th class="border-2 border-black text-left">REF#</th>
            <th class="border-2 border-black text-left">DEBIT</th>
            <th class="border-2 border-black text-left">CREDIT</th>
            <th class="border-2 border-black text-left">OUTSTANDING BALANCE</th>
            <th class="border-2 border-black text-left">REMARKS</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($cbu->payments as $payment)
            <tr class="border-2 border-black">
                <td class="border-2 border-black">{{ $payment->created_at->format('m/d/Y') }}</td>
                <td class="border-2 border-black">{{ $payment->reference_number }}</td>
                <td class="border-2 border-black"></td>
                <td class="border-2 border-black">{{ Filament\Support\format_money($payment->amount, 'PHP') }}</td>
                <td class="border-2 border-black">{{ Filament\Support\format_money($payment->running_balance, 'PHP') }}</td>
                <td class="border-2 border-black">{{ $payment->remarks }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No payments made.</td>
            </tr>
        @endforelse
    </tbody>
</table>
