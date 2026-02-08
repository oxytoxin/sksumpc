<div>
    <div class="mb-6">
        {{ $this->form }}
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded border border-blue-200">
            <h3 class="font-bold text-blue-800">Total Savings</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->totalSavingsBalance, 2) }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded border border-green-200">
            <h3 class="font-bold text-green-800">Total Imprests</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->totalImprestBalance, 2) }}</p>
        </div>

        <div class="bg-purple-50 p-4 rounded border border-purple-200">
            <h3 class="font-bold text-purple-800">Total Love Gifts</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->totalLoveGiftBalance, 2) }}</p>
        </div>
    </div>

    <x-app.cashier.reports.report-layout>
        <h3 class="text-xl font-bold mt-6 mb-4">Account Balances Summary (As of {{ $this->asOfDate->format('F d, Y') }})</h3>
        <table class="doc-table border border-black px-4 text-sm w-full">
            <thead>
                <tr>
                    <th class="doc-table-header-cell">Account Type</th>
                    <th class="doc-table-header-cell">Total Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="doc-table-cell">Savings</td>
                    <td class="doc-table-cell-right">{{ renumber_format($this->totalSavingsBalance) }}</td>
                </tr>
                <tr>
                    <td class="doc-table-cell">Imprests</td>
                    <td class="doc-table-cell-right">{{ renumber_format($this->totalImprestBalance) }}</td>
                </tr>
                <tr>
                    <td class="doc-table-cell">Love Gifts</td>
                    <td class="doc-table-cell-right">{{ renumber_format($this->totalLoveGiftBalance) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="doc-table-cell-right font-bold">Total:</td>
                    <td class="doc-table-cell-right font-bold">{{ renumber_format($this->totalAccountBalance) }}</td>
                </tr>
            </tfoot>
        </table>
    </x-app.cashier.reports.report-layout>
</div>
