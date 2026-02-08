<x-filament-panels::page>
    <div class="mb-6">
        {{ $this->form }}
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
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

        <div class="bg-red-50 p-4 rounded border border-red-200">
            <h3 class="font-bold text-red-800">Outstanding Loans</h3>
            <p class="text-2xl font-semibold">{{ number_format($this->totalOutstandingLoans, 2) }}</p>
        </div>
    </div>

    <x-app.cashier.reports.report-layout>
        @if($this->loanOverpaymentsExcess->isNotEmpty())
            <h3 class="text-xl font-bold mt-6 mb-4">Loan Overpayments (Excess Payments)</h3>
            <table class="doc-table border border-black px-4 text-sm w-full">
                <thead>
                    <tr>
                        <th class="doc-table-header-cell">Member</th>
                        <th class="doc-table-header-cell">Loan Type</th>
                        <th class="doc-table-header-cell">Reference</th>
                        <th class="doc-table-header-cell">Total Paid</th>
                        <th class="doc-table-header-cell">Overpayment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->loanOverpaymentsExcess as $item)
                        <tr>
                            <td class="doc-table-cell">{{ $item->loan->member->full_name }}</td>
                            <td class="doc-table-cell">{{ $item->loan->loan_type->name }}</td>
                            <td class="doc-table-cell">{{ $item->loan->reference_number }}</td>
                            <td class="doc-table-cell-right">{{ renumber_format($item->total_paid) }}</td>
                            <td class="doc-table-cell-right text-red-600 font-bold">{{ renumber_format($item->overpayment) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="doc-table-cell-right font-bold">Total Overpayments:</td>
                        <td class="doc-table-cell-right font-bold">{{ renumber_format($this->loanOverpaymentsExcess->sum('overpayment')) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif

        @if($this->loanOverpaymentsNegative->isNotEmpty())
            <h3 class="text-xl font-bold mt-6 mb-4">Loans with Negative Outstanding Balance</h3>
            <table class="doc-table border border-black px-4 text-sm w-full">
                <thead>
                    <tr>
                        <th class="doc-table-header-cell">Member</th>
                        <th class="doc-table-header-cell">Loan Type</th>
                        <th class="doc-table-header-cell">Reference</th>
                        <th class="doc-table-header-cell">Gross Amount</th>
                        <th class="doc-table-header-cell">Outstanding Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->loanOverpaymentsNegative as $loan)
                        <tr>
                            <td class="doc-table-cell">{{ $loan->member->full_name }}</td>
                            <td class="doc-table-cell">{{ $loan->loan_type->name }}</td>
                            <td class="doc-table-cell">{{ $loan->reference_number }}</td>
                            <td class="doc-table-cell-right">{{ renumber_format($loan->gross_amount) }}</td>
                            <td class="doc-table-cell-right text-red-600 font-bold">{{ renumber_format($loan->outstanding_balance) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="doc-table-cell-right font-bold">Total Negative Balance:</td>
                        <td class="doc-table-cell-right font-bold text-red-600">{{ renumber_format($this->loanOverpaymentsNegative->sum(fn ($l) => abs($l->outstanding_balance))) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif

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
                    <td class="doc-table-cell-right font-bold">{{ renumber_format($this->totalSavingsBalance + $this->totalImprestBalance + $this->totalLoveGiftBalance) }}</td>
                </tr>
            </tfoot>
        </table>

        <h3 class="text-xl font-bold mt-6 mb-4">Loan Balances (As of {{ $this->asOfDate->format('F d, Y') }})</h3>

        <div class="bg-gray-50 p-4 rounded border mb-4">
            <table class="doc-table border border-black px-4 text-sm">
                <tr>
                    <td class="doc-table-cell font-bold">Total Outstanding Loans:</td>
                    <td class="doc-table-cell-right font-bold text-2xl">{{ renumber_format($this->totalOutstandingLoans) }}</td>
                </tr>
                <tr>
                    <td class="doc-table-cell font-bold">Total Overpayments:</td>
                    <td class="doc-table-cell-right font-bold text-2xl text-red-600">{{ renumber_format($this->totalOverpaymentsAmount) }}</td>
                </tr>
            </table>
        </div>

        <table class="doc-table border border-black px-4 text-sm w-full">
            <thead>
                <tr>
                    <th class="doc-table-header-cell">Loan Type</th>
                    <th class="doc-table-header-cell">Count</th>
                    <th class="doc-table-header-cell">Total Outstanding</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->loanBalancesByType as $item)
                    <tr>
                        <td class="doc-table-cell">{{ $item->loan_type_name }}</td>
                        <td class="doc-table-cell-right">{{ $item->loan_count }}</td>
                        <td class="doc-table-cell-right">{{ renumber_format($item->total_outstanding) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="doc-table-cell-right font-bold">Total:</td>
                    <td class="doc-table-cell-right font-bold">{{ $this->loanBalancesByType->sum('loan_count') }}</td>
                    <td class="doc-table-cell-right font-bold">{{ renumber_format($this->loanBalancesByType->sum('total_outstanding')) }}</td>
                </tr>
            </tfoot>
        </table>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
