<x-filament-panels::page>
    {{ $this->form }}
    <div class="w-full">
        <x-app.cashier.reports.report-layout :signatories="$signatories ?? []" title="TRANSACTIONS LIST">
            <h3 class="-mt-4 mb-4 text-center text-xl font-semibold">{{ strtoupper($this->selected_account?->number) }} - {{ strtoupper($this->selected_account?->name) }}</h3>
            <div class="print:hidden" wire:loading.block>
                <p class="my-8 animate-pulse text-center text-lg font-semibold">Loading data...</p>
            </div>
            <table wire:loading.remove class="w-full text-xs">
                <thead>
                    <tr>
                        <th class="border border-black text-center">NO.</th>
                        <th class="border border-black px-2 text-left">MEMBER NAME</th>
                        <th class="border border-black px-2 text-left">DATE</th>
                        <th class="border border-black text-center">ACCOUNT NUMBER</th>
                        <th class="border border-black text-center">REFERENCE #</th>
                        <th class="border border-black px-2 text-center">DEBIT</th>
                        <th class="border border-black px-2 text-center">CREDIT</th>
                        <th class="border border-black text-center">RUNNING BALANCE</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $balance = $this->forwarded_balance?->total_debit * $this->selected_account?->account_type->debit_operator + $this->forwarded_balance?->total_credit * $this->selected_account?->account_type->credit_operator;
                    @endphp
                    <tr>
                        <th class="border border-black text-center"></th>
                        <td colspan="4" class="whitespace-nowrap border border-black px-2 text-left">FORWARDED BALANCE</td>
                        <td class="border border-black text-center">{{ renumber_format($this->forwarded_balance?->total_debit) }}</td>
                        <td class="border border-black text-center">{{ renumber_format($this->forwarded_balance?->total_credit) }}</td>
                        <td class="border border-black text-center">{{ renumber_format($balance) }}</td>
                    </tr>
                    @forelse ($this->table->getRecords() as $record)
                    @php
                    $balance += $record->debit * $this->selected_account?->account_type->debit_operator ?? 0;
                    $balance += $record->credit * $this->selected_account?->account_type->credit_operator ?? 0;
                    @endphp
                    <tr>
                        <th class="border border-black text-center">{{ $loop->iteration }}</th>
                        <td class="whitespace-nowrap border border-black px-2 text-left">{{ $record->payee }}</td>
                        <td class="whitespace-nowrap border border-black px-2 text-left">{{ $record->transaction_date->format('m/d/Y') }}</td>
                        <td class="whitespace-nowrap border border-black px-2 text-center">{{ $record->account->number }}</td>
                        <td class="whitespace-nowrap border border-black px-2 text-center">{{ $record->reference_number }}</td>
                        <td class="border border-black text-center">{{ renumber_format($record->debit) }}</td>
                        <td class="border border-black text-center">{{ renumber_format($record->credit) }}</td>
                        <td class="border border-black text-center">{{ renumber_format($balance) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="border border-black text-center">No transactions today.</td>
                    </tr>
                    @endforelse
                    <tr>
                        <th colspan="5" class="border border-black text-center">GRAND TOTAL</th>
                        <td class="border border-black text-center">
                            {{ renumber_format($this->table->getRecords()->sum('debit')) }}
                        </td>
                        <td class="border border-black text-center">
                            {{ renumber_format($this->table->getRecords()->sum('credit')) }}
                        </td>
                        <td class="border border-black text-center">
                            {{ renumber_format($balance) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-app.cashier.reports.report-layout>
    </div>
</x-filament-panels::page>