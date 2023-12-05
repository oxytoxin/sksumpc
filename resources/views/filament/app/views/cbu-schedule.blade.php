<div x-data>
    <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="text-xl text-center mt-4 print:text-[12pt] font-bold uppercase">CBU SCHEDULE AS OF {{ today()->format('F d, Y') }}</h4>
        <h5 class="text-center font-bold uppercase">{{ App\Models\MemberType::find($this->tableFilters['member_type_id']['value'])?->name }}</h5>
        <table class="w-full print:text-[8pt] mt-4">
            <thead>
                <tr>
                    <th class="text-center border border-black">Name</th>
                    <th class="text-center border border-black">No. of Shares Subscribed</th>
                    <th class="text-center border border-black">Amount of Shares Subscribed</th>
                    <th class="text-center border border-black">No. of Shares Paid</th>
                    <th class="text-center border border-black">Total Amount Paid-Up Capital Share Common</th>
                    <th class="text-center border border-black">Subscription Receivable Common</th>
                    <th class="text-center border border-black">Paid-Up Share Capital Common</th>
                    <th class="text-center border border-black">Deposit for Share Capital Subscription</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->table->getRecords() as $record)
                    <tr>
                        <td class="text-left px-4 border border-black whitespace-nowrap">{{ $record->alt_full_name }}</td>
                        <td class="text-center px-4 border border-black whitespace-nowrap">{{ $record->capital_subscriptions_sum_number_of_shares }}</td>
                        <td class="text-right px-4 border border-black whitespace-nowrap">{{ $record->capital_subscriptions_sum_amount_subscribed }}</td>
                        <td class="text-center px-4 border border-black whitespace-nowrap">{{ number_format($this->number_of_shares_paid($record), 2) }}</td>
                        <td class="text-right px-4 border border-black whitespace-nowrap">{{ $record->capital_subscription_payments_sum_amount }}</td>
                        <td class="text-right px-4 border border-black whitespace-nowrap">{{ $record->capital_subscriptions_sum_amount_subscribed - $record->capital_subscription_payments_sum_amount }}</td>
                        <td class="text-right px-4 border border-black whitespace-nowrap">{{ number_format($this->amount_shares_paid($record), 2) }}</td>
                        <td class="text-right px-4 border border-black whitespace-nowrap">{{ $record->capital_subscription_payments_sum_amount - $this->amount_shares_paid($record) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Schedule')">Print</x-filament::button>
    </div>
</div>
