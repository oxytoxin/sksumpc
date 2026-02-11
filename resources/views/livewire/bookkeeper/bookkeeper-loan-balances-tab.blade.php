<div>
    {{--    <div class="mb-6">--}}
    {{--        {{ $this->form }}--}}
    {{--    </div>--}}

    <div class="bg-red-50 p-4 rounded border mb-4">
        <table class="doc-table border border-black px-4 text-sm">
            <tr>
                <td class="doc-table-cell font-bold">Total Outstanding Loans:</td>
                <td class="doc-table-cell-right font-bold text-2xl">{{ renumber_format($this->totalOutstandingLoans) }}</td>
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
                    <td class="doc-table-cell-right">{{ renumber_format($item->total_outstanding) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td class="doc-table-cell-right font-bold">Total:</td>
                <td class="doc-table-cell-right font-bold">{{ $this->totalLoanCount }}</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->loanBalancesByType->sum('total_outstanding')) }}</td>
            </tr>
            </tfoot>
        </table>
    </x-app.cashier.reports.report-layout>
</div>
