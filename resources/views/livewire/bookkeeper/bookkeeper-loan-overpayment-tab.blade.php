<div>
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

        @if($this->loanOverpaymentsExcess->isEmpty() && $this->loanOverpaymentsNegative->isEmpty())
            <div class="text-center text-gray-500 py-8">
                No loan overpayments found.
            </div>
        @endif
    </x-app.cashier.reports.report-layout>
</div>
