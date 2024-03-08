<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title">
    <table class="w-full">
        <thead>
            <tr>
                <th class="text-center border border-black">NO.</th>
                <th class="text-center border border-black">MEMBER NAME</th>
                <th class="text-center border border-black">LOAN TYPE</th>
                <th class="text-center border border-black">REFERENCE #</th>
                <th class="text-center border border-black">AMOUNT</th>
                <th class="text-center border border-black">DATE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($this->table->getRecords() as $record)
                <tr>
                    <th class="text-center border border-black">{{ $loop->iteration }}</th>
                    <td class="text-center border border-black whitespace-nowrap px-2">
                        {{ $record->member->full_name }}
                    </td>
                    <td class="text-center border border-black">
                        {{ $record->loan->loan_type->name }}
                    </td>
                    <td class="text-center border border-black">
                        {{ $record->reference_number }}
                    </td>
                    <td class="text-center border border-black">
                        {{ renumber_format($record->amount, 2) }}</td>
                    <td class="text-center border border-black">
                        {{ $record->transaction_date?->format('m/d/Y') }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center border border-black">No transactions today.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
