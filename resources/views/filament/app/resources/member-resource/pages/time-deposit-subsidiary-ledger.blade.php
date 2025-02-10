@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div class="print:text-[10pt]" x-ref="print">
        <div class="text-center">
            <h1 class="font-bold">SKSU-MULTI-PURPOSE COOPERATIVE</h1>
            <h2>Access Campus, EJC Montilla, Tacurong City</h2>
        </div>
        <h2 class="my-4 text-center font-bold">SUBSIDIARY LEDGER FOR TIME DEPOSIT</h2>
        <div class="grid grid-cols-2">
            <div class="leading-4">
                <p>Account Name: <strong>{{ $time_deposit_account->name }}</strong></p>
                <p>Account Number: <strong>{{ $time_deposit_account->number }}</strong></p>
                <p>Open Date: {{ $time_deposit_account->time_deposit->transaction_date->format('F d, Y') }}</p>
                <p>Maturity Date: {{ $time_deposit_account->time_deposit->maturity_date->format('F d, Y') }}</p>
                <p>Interest Rate: {{ $time_deposit_account->time_deposit->interest_rate * 100 }}%</p>
                <p>TDC No.: {{ $time_deposit_account->time_deposit->tdc_number }}</p>
                <p>Interest Earned: {{ $time_deposit_account->time_deposit->interest_earned }}</p>
                <p>Interest On Maturity: <strong>P{{ number_format($time_deposit_account->time_deposit->interest, 2) }}</strong></p>
            </div>
        </div>
        <div class="mt-4">
            <table class="w-full print:text-[8pt]">
                <thead>
                    <tr>
                        <th class="border border-black px-4">Date</th>
                        <th class="border border-black px-4">Withdrawal</th>
                        <th class="border border-black px-4">Deposit</th>
                        <th class="border border-black px-4">Balance</th>
                        <th class="border border-black px-4">Remarks/Initial</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $balance = 0;
                    @endphp
                    @forelse ($time_deposit_account->transactions as $transaction)
                        @php
                            $balance += $transaction->credit - $transaction->debit;
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap border border-black px-4">{{ $transaction->transaction_date->format('F d, Y') }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">{{ renumber_format($transaction->debit, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">{{ renumber_format($transaction->credit, 2) }}</td>
                            <td class="whitespace-nowrap border border-black px-4 text-right">{{ renumber_format($balance, 2) }}</td>
                            <td class="border border-black px-4">{{ $transaction->remarks }}</td>
                        </tr>
                    @empty
                        <tr class="border border-black">
                            <td colspan="8" class="text-center">No payments made.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td class="border border-black px-4">Total</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($time_deposit_account->transactions->sum('debit'), 2) }}</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($time_deposit_account->transactions->sum('credit'), 2) }}</td>
                        <td class="border border-black px-4 text-right">{{ renumber_format($time_deposit_account->transactions->sum('credit') - $time_deposit_account->transactions->sum('debit'), 2) }}</td>
                        <td class="border border-black px-4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between">
            <div class="flex">
                <p>Prepared by:</p>
                <div class="ml-4 mt-12 flex flex-col items-center">
                    <p class="font-bold uppercase">{{ auth()->user()->name }}</p>
                    <p>MSO Officer</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-2">
        <x-filament::button wire:ignore href="{{ back()->getTargetUrl() }}" outlined tag="a">Back</x-filament::button>
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</x-filament-panels::page>
