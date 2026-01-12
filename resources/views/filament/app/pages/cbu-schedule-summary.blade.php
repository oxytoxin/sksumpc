<x-filament-panels::page>
    <div class="p-4 print:w-full print:p-4 print:text-[10pt] print:leading-tight" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="mt-4 text-center text-2xl font-bold uppercase print:text-[10pt]">CBU SCHEDULE SUMMARY AS OF {{ (date_create($transaction_date) ?? today())->format('F d, Y') }}</h4>
        <div class="print:hidden">{{ $this->form }}</div>
        <table class="mt-4 doc-table print:text-[8pt]">
            <thead>
                <tr>
                    <th class="doc-table-header-cell">NO.</th>
                    <th class="doc-table-header-cell">CAPITAL SHARE TYPE</th>
                    <th class="doc-table-header-cell">Paid Up Share Capital Common</th>
                    <th class="doc-table-header-cell">Deposit For Share Capital Subscription</th>
                    <th class="doc-table-header-cell">Total Amount Paid-Up Capital Share Common</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="doc-table-cell"></td>
                    <td class="doc-table-cell" colspan="4">COMMON SHARE</td>
                </tr>
                <tr>
                    <td class="doc-table-cell-center">1</td>
                    <td class="doc-table-cell">LABORATORY</td>
                    <td class="doc-table-cell-right">{{ number_format($this->laboratory_amounts['shares_paid'], 2) }}</td>
                    <td class="doc-table-cell-right">{{ number_format($this->laboratory_amounts['shares_deposit'], 2) }}</td>
                    <td class="doc-table-cell-right">{{ number_format($this->laboratory_amounts['amount_paid'], 2) }}</td>
                </tr>
                <tr>
                    <td class="doc-table-cell-center">2</td>
                    <td class="doc-table-cell">REGULAR MEMBERS</td>
                    <td class="doc-table-cell-right">{{ number_format($this->regular_amounts['shares_paid'], 2) }}</td>
                    <td class="doc-table-cell-right">{{ number_format($this->regular_amounts['shares_deposit'], 2) }}</td>
                    <td class="doc-table-cell-right">{{ number_format($this->regular_amounts['amount_paid'], 2) }}</td>
                </tr>
                <tr class="doc-table-row-total">
                    <td class="doc-table-cell" rowspan="2"></td>
                    <td class="doc-table-cell-right">TOTAL</td>
                    <td class="doc-table-cell-right">
                        {{ number_format($this->laboratory_amounts['shares_paid'] + $this->regular_amounts['shares_paid'], 2) }}
                    </td>
                    <td class="doc-table-cell-right">
                        {{ number_format($this->laboratory_amounts['shares_deposit'] + $this->regular_amounts['shares_deposit'], 2) }}
                    </td>
                    <td class="doc-table-cell-right">
                        {{ number_format($this->laboratory_amounts['amount_paid'] + $this->regular_amounts['amount_paid'], 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="doc-table-cell">PREFERRED SHARE</td>
                </tr>
                <tr>
                    <td class="doc-table-cell-center">1</td>
                    <td class="doc-table-cell">ASSOCIATE MEMBERS</td>
                    <td class="doc-table-cell-right">{{ number_format($this->associate_amounts['shares_paid'], 2) }}</td>
                    <td class="doc-table-cell-right">{{ number_format($this->associate_amounts['shares_deposit'], 2) }}</td>
                    <td class="doc-table-cell-right">{{ number_format($this->associate_amounts['amount_paid'], 2) }}</td>
                </tr>
                <tr class="doc-table-row-total">
                    <td class="doc-table-cell"></td>
                    <td class="doc-table-cell-right">GRAND TOTAL OF CAPITAL SHARE</td>
                    <td class="doc-table-cell-right">
                        {{ number_format($this->laboratory_amounts['shares_paid'] + $this->regular_amounts['shares_paid'] + $this->associate_amounts['shares_paid'], 2) }}
                    </td>
                    <td class="doc-table-cell-right">
                        {{ number_format($this->laboratory_amounts['shares_deposit'] + $this->regular_amounts['shares_deposit'] + $this->associate_amounts['shares_deposit'], 2) }}
                    </td>
                    <td class="doc-table-cell-right">
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
