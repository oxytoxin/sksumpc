<x-app.cashier.reports.report-layout :signatories="$signatories" :title="$report_title">
    <table class="w-full">
        <thead>
            <tr>
                <th class="border border-black text-center">NO.</th>
                <th class="border border-black text-center">MEMBER NAME</th>
                <th class="border border-black text-center">ACCOUNT #</th>
                <th class="border border-black text-center">LOAN TYPE</th>
                <th class="border border-black text-center">REFERENCE #</th>
                <th class="border border-black text-center">AMOUNT</th>
                <th class="border border-black text-center">PRINCIPAL</th>
                <th class="border border-black text-center">INTEREST</th>
                <th class="border border-black text-center">SURCHARGE</th>
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
                    <td class="border border-black text-center">
                        {{ $record->loan->loan_account->number }}
                    </td>
                    <td class="border border-black text-center">
                        {{ $record->loan->loan_type->name }}
                    </td>
                    <td class="border border-black text-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="border border-black text-center">{{ renumber_format($record->amount, 2) }}</td>
                    <td class="border border-black text-center">{{ renumber_format($record->principal_payment, 2) }}</td>
                    <td class="border border-black text-center">{{ renumber_format($record->interest_payment, 2) }}</td>
                    <td class="border border-black text-center">{{ renumber_format($record->surcharge_payment, 2) }}</td>
                    <td class="border border-black text-center">{{ $record->transaction_date?->format('m/d/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border border-black text-center">No transactions today.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
