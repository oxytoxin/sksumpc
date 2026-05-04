<div>
    <div class="my-6">
        {{ $this->form }}
    </div>

    <div class="bg-red-50 p-4 rounded border mb-4">
        <table class="doc-table border border-black px-4 text-sm">
            <tr>
                <td class="doc-table-cell font-bold">Total Overdue Loans:</td>
                <td class="doc-table-cell-right font-bold text-2xl">{{ renumber_format($this->totalOverdueCount) }}</td>
                <td class="doc-table-cell font-bold">Total Overdue Balance:</td>
                <td class="doc-table-cell-right font-bold text-2xl">{{ renumber_format($this->totalOverdueBalance, 4) }}</td>
            </tr>
        </table>
    </div>

    <x-app.cashier.reports.report-layout>
        <h3 class="text-xl font-bold mt-6 mb-4">Loan Aging Summary (As of {{ $this->asOfDate->format('F d, Y') }})</h3>

        <table class="doc-table border border-black px-4 text-sm w-full mb-8">
            <thead>
            <tr>
                <th class="doc-table-header-cell">Aging Period</th>
                <th class="doc-table-header-cell-right">Count</th>
                <th class="doc-table-header-cell-right">Total Outstanding</th>
            </tr>
            </thead>
            <tbody>
            @foreach($this->agingBuckets as $key => $bucket)
                <tr>
                    <td class="doc-table-cell font-bold">{{ $bucket['name'] }}</td>
                    <td class="doc-table-cell-right">{{ $bucket['count'] }}</td>
                    <td class="doc-table-cell-right">{{ renumber_format($bucket['total'], 4) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td class="doc-table-cell-right font-bold">Total:</td>
                <td class="doc-table-cell-right font-bold">{{ $this->totalOverdueCount }}</td>
                <td class="doc-table-cell-right font-bold">{{ renumber_format($this->totalOverdueBalance, 4) }}</td>
            </tr>
            </tfoot>
        </table>

        <h3 class="text-xl font-bold mt-8 mb-4">{{ $this->loanTypeLabel }} (As
            of {{ $this->asOfDate->format('F d, Y') }})</h3>

        @foreach($this->agingBuckets as $key => $bucket)
            @if($bucket['count'] > 0)
                <h4 class="text-lg font-bold mt-6 mb-2">{{ $bucket['name'] }}</h4>
                <table class="doc-table border border-black px-4 text-sm w-full mb-6">
                    <thead>
                    <tr>
                        <th class="doc-table-header-cell">Reference Number</th>
                        <th class="doc-table-header-cell">Member</th>
                        <th class="doc-table-header-cell">Loan Type</th>
                        <th class="doc-table-header-cell">Maturity Date</th>
                        <th class="doc-table-header-cell-right">Days Overdue</th>
                        <th class="doc-table-header-cell-right">Outstanding Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($bucket['loans'] as $loan)
                        <tr>
                            <td class="doc-table-cell">{{ $loan->reference_number }}</td>
                            <td class="doc-table-cell">{{ $loan->member?->full_name ?? '-' }}</td>
                            <td class="doc-table-cell">{{ $loan->loan_type?->name ?? '-' }}</td>
                            <td class="doc-table-cell">{{ $loan->maturity_date?->format('m/d/Y') ?? '-' }}</td>
                            <td class="doc-table-cell-right">{{ $this->asOfDate->diffInDays($loan->maturity_date) < 0 ? abs($this->asOfDate->diffInDays($loan->maturity_date)) : 0 }}</td>
                            <td class="doc-table-cell-right">{{ renumber_format($loan->outstanding_balance, 4) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="doc-table-cell" colspan="6">No loans in this aging period</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="doc-table-cell-right font-bold" colspan="5">Total:</td>
                        <td class="doc-table-cell-right font-bold">{{ renumber_format($bucket['total'], 4) }}</td>
                    </tr>
                    </tfoot>
                </table>
            @endif
        @endforeach
    </x-app.cashier.reports.report-layout>
</div>
