@php use App\Models\Loan; @endphp
<x-filament-panels::page>
    {{ $this->form }}

    <div class="mt-8">
        @if($data['loan_id'])
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
                    $loan = Loan::find($data['loan_id']);
                    $balance = $loan?->gross_amount;
                @endphp
                @foreach($loan?->payments as $payment)
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
                        <td class="table-cell">
                            <button wire:click="selectLoanPayment({{ $payment->id }})"
                                    class="border border-black px-2 my-2 w-full">
                                View Transactions
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if($loan_payment)
                <h3 class="mt-8">Transactions</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="table-cell">ID</th>
                        <th class="table-cell">Reference Number</th>
                        <th class="table-cell">Credit</th>
                        <th class="table-cell">Debit</th>
                        <th class="table-cell">Date</th>
                        <th class="table-cell"></th>
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
                            <td class="table-cell">
                                <button wire:click="selectLoanPayment({{ $transaction->id }})"
                                        class="border border-black px-2 my-2 w-full">
                                    View Transactions
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</x-filament-panels::page>
