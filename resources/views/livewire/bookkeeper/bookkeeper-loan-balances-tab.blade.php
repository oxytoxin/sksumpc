<div>
    <div class="my-6">
        {{ $this->form }}
    </div>

    <div class="bg-red-50 p-4 rounded border mb-4">
        <table class="doc-table border border-black px-4 text-sm">
            <tr>
                <td class="doc-table-cell font-bold">Total Outstanding Loans:</td>
                <td class="doc-table-cell-right font-bold text-2xl">{{ renumber_format($this->totalOutstandingLoans, 4) }}</td>
            </tr>
        </table>
    </div>

    <x-app.cashier.reports.report-layout>
        <h3 class="text-xl font-bold mt-6 mb-4">Loan Balances (As of {{ $this->asOfDate->format('F d, Y') }})</h3>

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
                    <td class="doc-table-cell-right">{{ renumber_format($item->total_outstanding, 4) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td class="doc-table-cell-right font-bold">Total:</td>
                <td class="doc-table-cell-right font-bold">{{ $this->totalLoanCount }}</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->loanBalancesByType->sum('total_outstanding'), 4) }}</td>
            </tr>
            </tfoot>
        </table>

        <h3 class="text-xl font-bold mt-8 mb-4">{{ $this->loanTypeLabel }} (As
            of {{ $this->asOfDate->format('F d, Y') }})</h3>
        <table class="doc-table border border-black px-4 text-sm w-full">
            <thead>
            <tr>
                <th class="doc-table-header-cell">Reference Number</th>
                <th class="doc-table-header-cell">Member</th>
                <th class="doc-table-header-cell">Loan Type</th>
                <th class="doc-table-header-cell">Gross Amount</th>
                <th class="doc-table-header-cell">Outstanding Balance</th>
            </tr>
            </thead>
            <tbody>
            @forelse($this->loans as $loan)
                <tr>
                    <td class="doc-table-cell">{{ $loan->reference_number }}</td>
                    <td class="doc-table-cell">{{ $loan->member?->full_name ?? '-' }}</td>
                    <td class="doc-table-cell">{{ $loan->loan_type?->name ?? '-' }}</td>
                    <td class="doc-table-cell-right">{{ renumber_format($loan->gross_amount) }}</td>
                    <td class="doc-table-cell-right">{{ renumber_format($loan->outstanding_balance, 4) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="doc-table-cell" colspan="5">No loans found</td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td class="doc-table-cell-right font-bold" colspan="4">Total:</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->loans->sum('outstanding_balance'), 4) }}</td>
            </tr>
            </tfoot>
        </table>
    </x-app.cashier.reports.report-layout>
</div>
