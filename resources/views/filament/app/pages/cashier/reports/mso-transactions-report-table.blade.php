<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title">
    <table class="w-full">
        <thead>
        <tr>
            <th class="border border-black text-center">NO.</th>
            <th class="border border-black text-center">MEMBER NAME</th>
            <th class="border border-black text-center">ACCOUNT NUMBER</th>
            <th class="border border-black text-center">REFERENCE #</th>
            <th class="border border-black text-center px-2">DEBIT</th>
            <th class="border border-black text-center px-2">CREDIT</th>
            <th class="border border-black text-center">DATE</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($this->table->getRecords() as $record)
            <tr>
                <th class="border border-black text-center">{{ $loop->iteration }}</th>
                <td class="whitespace-nowrap border border-black px-2 text-center">
                    {{ $record->member?->full_name ?? $record->payee }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-center">
                    {{ $record->account->number }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-center">
                    {{ $record->reference_number }}
                </td>
                <td class="border border-black text-center">
                    {{ renumber_format($record->debit, 2) }}</td>
                <td class="border border-black text-center">
                    {{ renumber_format($record->credit, 2) }}</td>
                <td class="border border-black text-center">
                    {{ $record->transaction_date?->format('m/d/Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="border border-black text-center">No transactions today.</td>
            </tr>
        @endforelse
        <tr>
            <th colspan="4" class="border border-black text-center">GRAND TOTAL</th>
            <td class="border border-black text-center">
                {{ renumber_format($this->table->getRecords()->sum('debit'), 2) }}</td>
            <td class="border border-black text-center">
                {{ renumber_format($this->table->getRecords()->sum('credit'), 2) }}</td>
            <td class="border border-black text-center">{{ renumber_format($this->table->getRecords()->sum('debit') - $this->table->getRecords()->sum('credit'), 2) }}</td>
        </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
