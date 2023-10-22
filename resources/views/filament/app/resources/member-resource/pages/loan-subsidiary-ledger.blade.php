@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div>
        <h3>Loan Type: {{ $loan->loan_type->name }}</h3>
        <h3>Amount Granted: {{ format_money($loan->gross_amount, 'PHP') }}</h3>
        <h3>Date Granted: {{ $loan->transaction_date->format('M d, Y') }}</h3>
        <h3>Maturity Date: {{ $loan->maturity_date->format('M d, Y') }}</h3>
        <h3>Terms of Payment: {{ $loan->number_of_terms }}</h3>
        <h3>Maturity Date: {{ $loan->interest_rate * 100 }}%</h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th class="border border-black px-2 text-left">Date</th>
                <th class="border border-black px-2 text-left">Ref. #</th>
                <th class="border border-black px-2">Amortization</th>
                <th class="border border-black px-2 text-right">Interest</th>
                <th class="border border-black px-2 text-right">Principal</th>
                <th class="border border-black px-2 text-right">Outstanding Balance</th>
                <th class="border border-black px-2">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-black px-2">{{ $loan->transaction_date->format('m/d/Y') }}</td>
                <td class="border border-black px-2">{{ $loan->reference_number }}</td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right">{{ format_money($loan->gross_amount, 'PHP') }}</td>
                <td class="border border-black px-2"></td>
            </tr>
            @foreach ($loan->payments as $key => $payment)
                <tr>
                    <td class="border border-black px-2">{{ $payment->transaction_date->format('m/d/Y') }}</td>
                    <td class="border border-black px-2">{{ $payment->reference_number }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($payment->amount, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($payment->interest, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($payment->principal, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($payment->running_balance, 'PHP') }}</td>
                    <td class="border border-black px-2"></td>
                </tr>
            @endforeach
            <tr>
                <td class="border border-black px-2"></td>
                <td class="border border-black px-2">TOTAL</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->payments)->sum('amount'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->payments)->sum('interest'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->payments)->sum('principal'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end">
        <x-filament::button href="{{ route('filament.app.resources.members.view', ['record' => $loan->member, 'tab' => '-loan-tab']) }}" tag="a">Back to Loans</x-filament::button>
    </div>
</x-filament-panels::page>
