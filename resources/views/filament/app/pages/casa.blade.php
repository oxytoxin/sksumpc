@php use App\Models\Loan; @endphp
<x-filament-panels::page>
    {{ $this->form }}

    <div class="mt-8">
        @if($data['loan_id'])
            <table>
                <thead>
                <tr>
                    <th class="table-cell">Reference Number</th>
                    <th class="table-cell">Amount</th>
                    <th class="table-cell">Principal</th>
                    <th class="table-cell">Interest</th>
                    <th class="table-cell">Date</th>
                    <th class="table-cell"></th>
                </tr>
                </thead>
                <tbody>
                @foreach(Loan::find($data['loan_id'])->payments as $payment)
                    <tr>
                        <td class="table-cell">{{ $payment->reference_number }}</td>
                        <td class="table-cell">{{ $payment->amount }}</td>
                        <td class="table-cell">{{ $payment->principal_payment }}</td>
                        <td class="table-cell">{{ $payment->interest_payment }}</td>
                        <td class="table-cell">{{ $payment->transaction_date->format('m/d/Y') }}</td>
                        <td class="table-cell">
                            <button wire:click="selectLoanPayment({{ $payment->id }})"
                                    class="border border-black px-2 my-2 w-full">select
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-filament-panels::page>
