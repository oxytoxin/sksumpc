<div>
    <div class="my-6">
        {{ $this->form }}
    </div>

    <div class="grid grid-cols-6 gap-4 my-6">
        <div class="bg-blue-50 p-4 rounded border border-blue-200">
            <h3 class="font-bold text-blue-800">Regular Savings</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->regularSavingsBalance, 2) }}</p>
        </div>

        <div class="bg-cyan-50 p-4 rounded border border-cyan-200">
            <h3 class="font-bold text-cyan-800">Associate Savings</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->associateSavingsBalance, 2) }}</p>
        </div>

        <div class="bg-teal-50 p-4 rounded border border-teal-200">
            <h3 class="font-bold text-teal-800">Laboratory Savings</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->laboratorySavingsBalance, 2) }}</p>
        </div>

        <div class="bg-orange-50 p-4 rounded border border-orange-200">
            <h3 class="font-bold text-orange-800">Time Deposits</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->timeDepositBalance, 2) }}</p>
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
        <h3 class="text-xl font-bold mt-6 mb-4">Account Balances Summary (As of {{ $this->asOfDate->format('F d, Y') }}
            )</h3>
        <table class="doc-table border border-black px-4 text-sm w-full">
            <thead>
            <tr>
                <th class="doc-table-header-cell">Account Type</th>
                <th class="doc-table-header-cell-right">Count</th>
                <th class="doc-table-header-cell-right">Total Balance</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="doc-table-cell">Regular Savings (1011, 2111)</td>
                <td class="doc-table-cell-right">{{ $this->regularSavingsAccountCount }}</td>
                <td class="doc-table-cell-right">{{ renumber_format($this->regularSavingsBalance) }}</td>
            </tr>
            <tr>
                <td class="doc-table-cell">Associate Savings (1012)</td>
                <td class="doc-table-cell-right">{{ $this->associateSavingsAccountCount }}</td>
                <td class="doc-table-cell-right">{{ renumber_format($this->associateSavingsBalance) }}</td>
            </tr>
            <tr>
                <td class="doc-table-cell">Laboratory Savings (1013)</td>
                <td class="doc-table-cell-right">{{ $this->laboratorySavingsAccountCount }}</td>
                <td class="doc-table-cell-right">{{ renumber_format($this->laboratorySavingsBalance) }}</td>
            </tr>
            <tr>
                <td class="doc-table-cell">Time Deposits</td>
                <td class="doc-table-cell-right">{{ $this->timeDepositAccountCount }}</td>
                <td class="doc-table-cell-right">{{ renumber_format($this->timeDepositBalance) }}</td>
            </tr>
            <tr>
                <td class="doc-table-cell">Imprests</td>
                <td class="doc-table-cell-right">{{ $this->imprestAccountCount }}</td>
                <td class="doc-table-cell-right">{{ renumber_format($this->totalImprestBalance) }}</td>
            </tr>
            <tr>
                <td class="doc-table-cell">Love Gifts</td>
                <td class="doc-table-cell-right">{{ $this->loveGiftAccountCount }}</td>
                <td class="doc-table-cell-right">{{ renumber_format($this->totalLoveGiftBalance) }}</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td class="doc-table-cell-right font-bold">Total:</td>
                <td class="doc-table-cell-right font-bold">{{ $this->regularSavingsAccountCount + $this->associateSavingsAccountCount + $this->laboratorySavingsAccountCount + $this->timeDepositAccountCount + $this->imprestAccountCount + $this->loveGiftAccountCount }}</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->totalAccountBalance) }}</td>
            </tr>
            </tfoot>
        </table>

        <h3 class="text-xl font-bold mt-8 mb-4">
            {{ $this->savingsTypeLabel }} Accounts (As of {{ $this->asOfDate->format('F d, Y') }})
        </h3>
        <table class="doc-table border border-black px-4 text-sm w-full">
            <thead>
            <tr>
                <th class="doc-table-header-cell">Account Number</th>
                <th class="doc-table-header-cell">Member</th>
                <th class="doc-table-header-cell-right">Balance</th>
            </tr>
            </thead>
            <tbody>
            @forelse($this->accountsWithBalances as $account)
                <tr>
                    <td class="doc-table-cell">{{ $account->number }}</td>
                    <td class="doc-table-cell">{{ $account->member?->full_name ?? '-' }}</td>
                    <td class="doc-table-cell-right">{{ renumber_format($account->balance) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="doc-table-cell" colspan="4">No accounts found</td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td class="doc-table-cell-right font-bold" colspan="2">Total:</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->accountsWithBalances->sum('balance')) }}</td>
            </tr>
            </tfoot>
        </table>
    </x-app.cashier.reports.report-layout>
</div>
