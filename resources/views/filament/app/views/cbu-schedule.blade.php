<div x-data>
    <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading/>
        <h4 class="mt-4 text-center text-xl font-bold uppercase print:text-[12pt]">{{ $this->gender }} {{ $this->member_type }} CBU SUMMARY SCHEDULE AS OF
            {{ today()->format('F d, Y') }}</h4>
        <h5 class="text-center font-bold uppercase">
            {{ App\Models\MemberType::find($this->tableFilters['member_type_id']['value'])?->name }}</h5>
        <table class="mt-4 w-full print:text-[8pt]">
            <thead>
            <tr>
                <th class="border border-black text-center">No.</th>
                <th class="border border-black text-center">Name</th>
                <th class="border border-black text-center">No. of Shares Subscribed</th>
                <th class="border border-black text-center">Amount of Shares Subscribed</th>
                <th class="border border-black text-center">No. of Shares Paid</th>
                <th class="border border-black text-center">Total Amount Paid-Up Capital Share Common</th>
                <th class="border border-black text-center">Subscription Receivable Common</th>
                <th class="border border-black text-center">Paid-Up Share Capital Common</th>
                <th class="border border-black text-center">Deposit for Share Capital Subscription</th>
            </tr>
            </thead>
            <tbody class="relative">
            @php
                $totals['capital_subscriptions_sum_number_of_shares'] = 0;
                $totals['capital_subscriptions_sum_amount_subscribed'] = 0;
                $totals['number_of_shares_paid'] = 0;
                $totals['capital_subscription_payments_sum_amount'] = 0;
                $totals['capital_subscriptions_sum_receivable'] = 0;
                $totals['amount_shares_paid'] = 0;
                $totals['capital_subscriptions_sum_deposit'] = 0;
            @endphp
            @foreach ($this->table->getRecords() as $record)
                <tr wire:loading.remove>
                    <td class="whitespace-nowrap border border-black px-4 text-center">{{ $loop->iteration }}</td>
                    <td class="whitespace-nowrap border border-black px-4 text-left">{{ $record->alt_full_name }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-4 text-center">
                        {{ round($record->capital_subscriptions_sum_number_of_shares, 0) }}</td>
                    <td class="whitespace-nowrap border border-black px-4 text-right">
                        {{ renumber_format($record->capital_subscriptions_sum_amount_subscribed, 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-4 text-center">
                        {{ round($this->number_of_shares_paid($record), 0) }}</td>
                    <td class="whitespace-nowrap border border-black px-4 text-right">
                        {{ renumber_format($record->capital_subscription_payments_sum_amount, 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-4 text-right">
                        {{ renumber_format($record->capital_subscriptions_sum_amount_subscribed - $record->capital_subscription_payments_sum_amount, 2) }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-4 text-right">
                        {{ renumber_format($this->amount_shares_paid($record), 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-4 text-right">
                        {{ renumber_format($record->capital_subscription_payments_sum_amount - $this->amount_shares_paid($record), 2) }}
                    </td>
                </tr>
                @php
                    $totals['capital_subscriptions_sum_number_of_shares'] += $record->capital_subscriptions_sum_number_of_shares;
                    $totals['capital_subscriptions_sum_amount_subscribed'] += $record->capital_subscriptions_sum_amount_subscribed;
                    $totals['number_of_shares_paid'] += $this->number_of_shares_paid($record);
                    $totals['capital_subscription_payments_sum_amount'] += $record->capital_subscription_payments_sum_amount;
                    $totals['capital_subscriptions_sum_receivable'] += $record->capital_subscriptions_sum_amount_subscribed - $record->capital_subscription_payments_sum_amount;
                    $totals['amount_shares_paid'] += $this->amount_shares_paid($record);
                    $totals['capital_subscriptions_sum_deposit'] += $record->capital_subscription_payments_sum_amount - $this->amount_shares_paid($record);
                @endphp
            @endforeach
            <tr wire:loading.remove>
                <td colspan="2" class="whitespace-nowrap border border-black px-4 text-left font-bold">TOTAL</td>
                <td class="whitespace-nowrap border border-black px-4 text-center font-bold">{{ round($totals['capital_subscriptions_sum_number_of_shares'], 0) }}</td>
                <td class="whitespace-nowrap border border-black px-4 text-right font-bold">{{ renumber_format($totals['capital_subscriptions_sum_amount_subscribed'], 2) }}</td>
                <td class="whitespace-nowrap border border-black px-4 text-center font-bold">{{ round($totals['number_of_shares_paid'], 0) }}</td>
                <td class="whitespace-nowrap border border-black px-4 text-right font-bold">{{ renumber_format($totals['capital_subscription_payments_sum_amount'], 2) }}</td>
                <td class="whitespace-nowrap border border-black px-4 text-right font-bold">
                    {{ renumber_format($totals['capital_subscriptions_sum_receivable'], 2) }}
                </td>
                <td class="whitespace-nowrap border border-black px-4 text-right font-bold">{{ renumber_format($totals['amount_shares_paid'], 2) }}</td>
                <td class="whitespace-nowrap border border-black px-4 text-right font-bold">{{ renumber_format($totals['capital_subscriptions_sum_deposit'], 2) }}</td>
            </tr>
            <tr class="hidden" wire:loading.class.remove="hidden">
                <td colspan="9" class="whitespace-nowrap border border-black px-4 text-center font-bold">Loading data...</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Schedule')">Print</x-filament::button>
    </div>
</div>
