<div>
    @php
        use function Filament\Support\format_money;
    @endphp
    <div>
        {{ $this->form }}
    </div>
    <div class="mt-8">
        <table class="w-full border border-black">
            <thead>
                <tr>
                    <th class="border border-black">DATE</th>
                    <th class="border border-black">REFERENCE</th>
                    <th class="border border-black">DEBIT</th>
                    <th class="border border-black">CREDIT</th>
                    <th class="border border-black">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $balance = $cash_beginning?->amount ?? 0;
                @endphp
                <tr>
                    <td class="border border-black px-4 text-center">{{ date_create($data['transaction_date'])->format('m/d/Y') }}</td>
                    <td class="border border-black px-4 text-center">CASH BEGINNING</td>
                    <td class="border border-black px-4"></td>
                    <td class="border border-black px-4 text-right">{{ format_money($balance, 'PHP') }}</td>
                    <td class="border border-black px-4 text-right">{{ format_money($balance, 'PHP') }}</td>
                </tr>
                @forelse ($withdrawals as $withdrawal)
                    @php
                        $balance -= $withdrawal->withdrawal;
                    @endphp
                    <tr>
                        <td class="border border-black px-4 text-center">{{ date_create($withdrawal->transaction_date)->format('m/d/Y') }}</td>
                        <td class="border border-black px-4 text-center">{{ $withdrawal->reference_number }}</td>
                        <td class="border border-black px-4 text-right">{{ format_money($withdrawal->withdrawal, 'PHP') }}</td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4 text-right">{{ format_money($balance, 'PHP') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center font-bold">No withdrawals found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tr>
                <td class="border border-black px-4 text-center">{{ date_create($data['transaction_date'])->format('m/d/Y') }}</td>
                <td class="border border-black px-4 text-center">CASH END</td>
                <td class="border border-black px-4"></td>
                <td class="border border-black px-4 text-right">{{ format_money($balance, 'PHP') }}</td>
                <td class="border border-black px-4 text-right">{{ format_money($balance, 'PHP') }}</td>
            </tr>
        </table>
    </div>
</div>
