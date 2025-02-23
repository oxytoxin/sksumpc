<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title">
    <table class="w-full text-xs">
        <thead>
            <tr>
                <th class="border border-black text-center">NO.</th>
                <th class="border border-black px-2 text-left">MEMBER NAME</th>
                <th class="border border-black px-2 text-left">DATE</th>
                <th class="border border-black px-2 text-left">PAYEE</th>
                <th class="border border-black text-center">ACCOUNT NAME</th>
                <th class="border border-black text-center">ACCOUNT NUMBER</th>
                <th class="border border-black text-center">REFERENCE #</th>
                <th class="border border-black px-2 text-center">DEPOSIT</th>
                <th class="border border-black px-2 text-center">WITHDRAWAL</th>
                <th class="border border-black text-center">RUNNING BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @php
            $balance = 0;
            $total_debit = 0;
            $total_credit = 0;
            @endphp
            @forelse ($this->table->getRecords() as $record)
            @php
            $balance = $balance + ($record->debit ?? 0) - ($record->credit ?? 0);
            $total_debit += $record->debit ?? 0;
            $total_credit += $record->credit ?? 0;
            @endphp
            <tr>
                <th class="border border-black text-center">{{ $loop->iteration }}</th>
                <td class="whitespace-nowrap border border-black px-2 text-left">
                    {{ $record->member?->full_name }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-left">
                    {{ $record->transaction_date->format('m/d/Y') }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-left">
                    {{ $record->payee }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-center">
                    {{ $record->account->name }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-center">
                    {{ $record->account->number }}
                </td>
                <td class="whitespace-nowrap border border-black px-2 text-center">
                    {{ $record->reference_number }}
                </td>
                <td class="border border-black text-center">{{ renumber_format($record->credit, 2) }}</td>
                <td class="border border-black text-center">{{ renumber_format($record->debit, 2) }}</td>
                <td class="border border-black text-center">{{ renumber_format($balance, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="border border-black text-center">No transactions today.</td>
            </tr>
            @endforelse
            <tr>
                <th colspan="7" class="border border-black text-center">GRAND TOTAL</th>
                <td class="border border-black text-center font-bold">
                    {{ renumber_format($total_credit, 2) }}
                </td>
                <td class="border border-black text-center font-bold">
                    {{ renumber_format($total_debit, 2) }}
                </td>
                <td class="border border-black text-center font-bold">{{ renumber_format($balance, 2) }}</td>
            </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>