@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div>
        <h3>Member's Name: {{ $loan->member->full_name }}</h3>
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
                <th class="border border-black px-2">Amortization</th>
                <th class="border border-black px-2 text-right">Interest</th>
                <th class="border border-black px-2 text-right">Principal</th>
                <th class="border border-black px-2 text-right">Arrears</th>
                <th class="border border-black px-2 text-right">Outstanding Balance</th>
                <th class="border border-black px-2">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-black px-2">{{ $loan->transaction_date->format('m/d/Y') }}</td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right">{{ format_money($loan->gross_amount, 'PHP') }}</td>
                <td class="border border-black px-2"></td>
            </tr>
            @foreach ($loan->paid_loan_amortizations as $key => $loan_amortization)
                <tr>
                    <td class="border border-black px-2">{{ $loan_amortization->date->format('F Y') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->amount_paid, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->amount_paid > $loan_amortization->interest ? $loan_amortization->interest : $loan_amortization->amount_paid, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->principal_payment, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->arrears, 'PHP') }}</td>
                    <td class="border border-black px-2 text-right">{{ format_money($loan_amortization->outstanding_balance + $loan_amortization->arrears, 'PHP') }}</td>
                    <td class="border border-black px-2"></td>
                </tr>
            @endforeach
            <tr>
                <td class="border border-black px-2">TOTAL</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->paid_loan_amortizations)->sum('amount_paid'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->paid_loan_amortizations)->sum('interest'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->paid_loan_amortizations)->sum('principal_payment'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right">{{ format_money(collect($loan->paid_loan_amortizations)->sum('arrears'), 'PHP') }}</td>
                <td class="border border-black px-2 text-right"></td>
                <td class="border border-black px-2 text-right"></td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end">
        <x-filament::button wire:ignore href="{{ back()->getTargetUrl() }}" outlined tag="a">Back</x-filament::button>
    </div>
</x-filament-panels::page>
