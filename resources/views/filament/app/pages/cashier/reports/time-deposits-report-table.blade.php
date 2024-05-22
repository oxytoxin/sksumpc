<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title">
    <table class="w-full">
        <thead>
            <tr>
                <th class="border border-black text-center">NO.</th>
                <th class="border border-black text-center">MEMBER NAME</th>
                <th class="border border-black text-center">ACCOUNT NUMBER</th>
                <th class="border border-black text-center">REFERENCE #</th>
                <th class="border border-black text-center">DEPOSIT</th>
                <th class="border border-black text-center">WITHDRAWAL</th>
                <th class="border border-black text-center">DATE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($this->table->getRecords() as $record)
                <tr>
                    <th class="border border-black text-center">{{ $loop->iteration }}</th>
                    <td class="whitespace-nowrap border border-black px-2 text-center">
                        {{ $record->member->full_name }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-center">
                        {{ $record->account->number }}
                    </td>
                    <td class="border border-black text-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="border border-black text-center">
                        {{ renumber_format($record->credit, 2) }}</td>
                    <td class="border border-black text-center">
                        {{ renumber_format($record->debit, 2) }}</td>
                    <td class="border border-black text-center">
                        {{ $record->transaction_date?->format('m/d/Y') }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border border-black text-center">No transactions today.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
