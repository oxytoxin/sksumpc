<x-filament-panels::page>
    <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h1 class="text-center font-bold">SKSU-MPC {{ $loan_billing->loan_type->name }} Billing Statement <br> as of {{ $loan_billing->billable_date }}</h1>
        <table class="mt-4 w-full print:text-[8pt]">
            <thead>
                <tr class="border-y border-black">
                    <th>NO.</th>
                    <th>MEMBER #</th>
                    <th>NAME OF EMPLOYEE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $payments = $this->loan_billing->loan_billing_payments()->join('members', 'loan_billing_payments.member_id', 'members.id')->selectRaw('loan_billing_payments.*, members.alt_full_name as member_name')->orderBy('member_name')->get();
                @endphp
                @forelse ($payments as $payment)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $payment->member->mpc_code }}</td>
                        <td class="text-center">{{ $payment->member->alt_full_name }}</td>
                        <td class="text-center">{{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>

    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Billing')">Print</x-filament::button>
    </div>
</x-filament-panels::page>
