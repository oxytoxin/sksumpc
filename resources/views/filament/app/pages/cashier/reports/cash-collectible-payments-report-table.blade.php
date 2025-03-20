<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title">
    <table class="w-full">
        <thead>
            <tr>
                <th class="border border-black text-center">NO.</th>
                <th class="border border-black text-center">MEMBER NAME</th>
                <th class="border border-black text-center">CASH COLLECTIBLE</th>
                <th class="border border-black text-center">REFERENCE #</th>
                <th class="border border-black text-center">AMOUNT</th>
                <th class="border border-black text-center">DATE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($this->table->getRecords() as $record)
                <tr>
                    <th class="border border-black text-center">{{ $loop->iteration }}</th>
                    <td class="whitespace-nowrap border border-black px-2 text-center">
                        {{ $record->payee }}
                    </td>
                    <td class="border border-black text-center">
                        {{ $record->account->name }}
                    </td>
                    <td class="border border-black text-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="border border-black text-center">
                        {{ renumber_format($record->credit, 2) }}</td>
                    <td class="border border-black text-center">
                        {{ $record->transaction_date?->format('m/d/Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="border border-black text-center" colspan="5">No transactions today.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
