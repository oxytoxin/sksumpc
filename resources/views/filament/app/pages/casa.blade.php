@php use App\Models\Loan; @endphp
<x-filament-panels::page>
    {{ $this->form }}

    <div class="mt-8">

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
            <table class="doc-table">
                <thead>
                <tr>
                    <th class="doc-table-header-cell">ID</th>
                    <th class="doc-table-header-cell">Reference Number</th>
                    <th class="doc-table-header-cell">Amount</th>
                    <th class="doc-table-header-cell">Principal</th>
                    <th class="doc-table-header-cell">Interest</th>
                    <th class="doc-table-header-cell">Outstanding Balance</th>
                    <th class="doc-table-header-cell">Date</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $balance = $loan?->gross_amount;
                @endphp
                @foreach($loan?->payments as $payment)
                    @php
                        $balance -= $payment->principal_payment;
                    @endphp
                    <tr>
                        <td class="doc-table-cell">{{ $payment->id }}</td>
                        <td class="doc-table-cell">{{ $payment->reference_number }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->amount) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->principal_payment) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->interest_payment) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($balance) }}</td>
                        <td class="doc-table-cell">{{ $payment->transaction_date->format('m/d/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <h3>Simulated Payments</h3>
            <table class="doc-table">
                <thead>
                <tr>
                    <th class="doc-table-header-cell">ID</th>
                    <th class="doc-table-header-cell">Reference Number</th>
                    <th class="doc-table-header-cell">Amount</th>
                    <th class="doc-table-header-cell">Principal</th>
                    <th class="doc-table-header-cell">Interest</th>
                    <th class="doc-table-header-cell">Outstanding Balance</th>
                    <th class="doc-table-header-cell">Date</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $balance = $loan?->gross_amount;
                @endphp
                @foreach($this->simulated_payments as $payment)
                    <tr>
                        <td class="doc-table-cell">{{ $payment->id }}</td>
                        <td class="doc-table-cell">{{ $payment->reference_number }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->amount) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->principal_payment) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->interest_payment) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($payment->balance) }}</td>
                        <td class="doc-table-cell">{{ $payment->transaction_date->format('m/d/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h3 class="mt-8">Transactions</h3>
            <table class="doc-table">
                <thead>
                <tr>
                    <th class="doc-table-header-cell">ID</th>
                    <th class="doc-table-header-cell">Reference Number</th>
                    <th class="doc-table-header-cell">Credit</th>
                    <th class="doc-table-header-cell">Debit</th>
                    <th class="doc-table-header-cell">Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($this->loan_payment_transactions as $transaction)
                    <tr>
                        <td class="doc-table-cell">{{ $transaction->id }}</td>
                        <td class="doc-table-cell">{{ $transaction->reference_number }}</td>
                        <td class="doc-table-cell">{{ renumber_format($transaction->credit) }}</td>
                        <td class="doc-table-cell">{{ renumber_format($transaction->debit) }}</td>
                        <td class="doc-table-cell">{{ $transaction->transaction_date->format('m/d/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-filament-panels::page>
