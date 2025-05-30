<x-app.cashier.reports.report-layout :signatories="$this->getSignatories()" :title="$report_title">
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
                    <td class="whitespace-nowrap border border-black px-2 text-center">
                        {{ $record->reference_number }}
                    </td>
                    <td class="border border-black text-center">{{ renumber_format($record->deposit, 2) }}</td>
                    <td class="border border-black text-center">{{ renumber_format($record->withdrawal, 2) }}</td>
                    <td class="border border-black text-center">{{ $record->transaction_date?->format('m/d/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border border-black text-center" colspan="7">No transactions today.</td>
                </tr>
            @endforelse
            <tr>
                <th class="border border-black text-center" colspan="4">GRAND TOTAL</th>
                <td class="border border-black text-center">{{ renumber_format($this->table->getRecords()->sum('deposit'), 2) }}</td>
                <td class="border border-black text-center">{{ renumber_format($this->table->getRecords()->sum('withdrawal'), 2) }}</td>
                <td class="border border-black text-center">{{ renumber_format($this->table->getRecords()->sum('amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
</x-app.cashier.reports.report-layout>
