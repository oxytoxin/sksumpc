<x-filament-panels::page>
    <div class="p-4 print:w-full print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="mt-4 text-center text-2xl font-bold uppercase print:text-[10pt]">CBU SCHEDULE SUMMARY AS OF {{ $this->date_range ?? today()->format('F d, Y') }}</h4>
        <div class="print:hidden">{{ $this->form }}</div>
        <table class="mt-4 w-full print:text-[8pt]">
            <thead>
                <tr>
                    <th class="border border-black px-2">NO.</th>
                    <th class="border border-black px-2">CAPITAL SHARE TYPE</th>
                    <th class="border border-black px-2">Paid Up Share Capital Common</th>
                    <th class="border border-black px-2">Deposit For Share Capital Subscription</th>
                    <th class="border border-black px-2">Total Amount Paid-Up Capital Share Common</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black px-2"></td>
                    <td class="border border-black px-2" colspan="4">COMMON SHARE</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 text-center">1</td>
                    <td class="whitespace-nowrap border border-black px-2">LABORATORY</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->laboratory_amounts['shares_paid'], 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->laboratory_amounts['shares_deposit'], 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->laboratory_amounts['amount_paid'], 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 text-center">2</td>
                    <td class="whitespace-nowrap border border-black px-2">REGULAR MEMBERS</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->regular_amounts['shares_paid'], 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->regular_amounts['shares_deposit'], 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->regular_amounts['amount_paid'], 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-black px-2" rowspan="2"></td>
                    <td class="border border-black px-2 text-right">TOTAL</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">
                        {{ number_format($this->laboratory_amounts['shares_paid'] + $this->regular_amounts['shares_paid'], 2) }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">
                        {{ number_format($this->laboratory_amounts['shares_deposit'] + $this->regular_amounts['shares_deposit'], 2) }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">
                        {{ number_format($this->laboratory_amounts['amount_paid'] + $this->regular_amounts['amount_paid'], 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="border border-black px-2">PREFERRED SHARE</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 text-center">1</td>
                    <td class="whitespace-nowrap border border-black px-2">ASSOCIATE MEMBERS</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->associate_amounts['shares_paid'], 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->associate_amounts['shares_deposit'], 2) }}</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">{{ number_format($this->associate_amounts['amount_paid'], 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-black px-2"></td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">GRAND TOTAL OF CAPITAL SHARE</td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">
                        {{ number_format($this->laboratory_amounts['shares_paid'] + $this->regular_amounts['shares_paid'] + $this->associate_amounts['shares_paid'], 2) }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">
                        {{ number_format($this->laboratory_amounts['shares_deposit'] + $this->regular_amounts['shares_deposit'] + $this->associate_amounts['shares_deposit'], 2) }}
                    </td>
                    <td class="whitespace-nowrap border border-black px-2 text-right">
                        {{ number_format($this->laboratory_amounts['amount_paid'] + $this->regular_amounts['amount_paid'] + $this->associate_amounts['amount_paid'], 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Savings Subsidiary Ledger')">Print</x-filament::button>
    </div>
</x-filament-panels::page>
