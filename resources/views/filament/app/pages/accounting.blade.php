@php use App\Models\Loan; @endphp
<x-filament-panels::page>
    {{ $this->form }}

    @if($data['loan_id'])
        @php
            $loan = Loan::find($data['loan_id']);
        @endphp
        <div class="my-8">
            <h3>Loan Balance: {{ renumber_format($loan->outstanding_balance) }}</h3>
            <h3>Total Amount Paid: {{ renumber_format($loan->payments->sum('amount')) }}</h3>
            <h3>Total Transactions Credit:
                {{ renumber_format($this->loan_payment_transactions->sum('credit')) }}
            </h3>
            <h3>Total Transactions Debit:
                {{ renumber_format($this->loan_payment_transactions->sum('debit')) }}
            </h3>
        </div>
        <h3>Payments</h3>
        <table>
            <thead>
            <tr>
                <th class="table-cell">ID</th>
                <th class="table-cell">Reference Number</th>
                <th class="table-cell">Amount</th>
                <th class="table-cell">Principal</th>
                <th class="table-cell">Interest</th>
                <th class="table-cell">Outstanding Balance</th>
                <th class="table-cell">Date</th>
                <th class="table-cell"></th>
            </tr>
            </thead>
            <tbody>
            @php
                $balance = $loan?->gross_amount;
            @endphp
            @foreach($this->loan_payments as $payment)
                @php
                    $balance -= $payment->principal_payment;
                @endphp
                <tr>
                    <td class="table-cell">{{ $payment->id }}</td>
                    <td class="table-cell">{{ $payment->reference_number }}</td>
                    <td class="table-cell">{{ renumber_format($payment->amount) }}</td>
                    <td class="table-cell">{{ renumber_format($payment->principal_payment) }}</td>
                    <td class="table-cell">{{ renumber_format($payment->interest_payment) }}</td>
                    <td class="table-cell">{{ renumber_format($balance) }}</td>
                    <td class="table-cell">{{ $payment->transaction_date->format('m/d/Y') }}</td>
                    <td class="table-cell py-2">
                        <button wire:click="selectPayment({{ $payment->id }})" class="w-full border border-black">
                            Select
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h3 class="mt-8">Transactions</h3>
        <table>
            <thead>
            <tr>
                <th class="table-cell">ID</th>
                <th class="table-cell">Reference Number</th>
                <th class="table-cell">Credit</th>
                <th class="table-cell">Debit</th>
                <th class="table-cell">Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($this->loan_payment_transactions as $transaction)
                <tr>
                    <td class="table-cell">{{ $transaction->id }}</td>
                    <td class="table-cell">{{ $transaction->reference_number }}</td>
                    <td class="table-cell">{{ renumber_format($transaction->credit) }}</td>
                    <td class="table-cell">{{ renumber_format($transaction->debit) }}</td>
                    <td class="table-cell">{{ $transaction->transaction_date->format('m/d/Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif
</x-filament-panels::page>
