<div>
    <h1 class="text-center font-bold text-xl my-2">TRIAL BALANCE REPORT</h1>
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th class="border border-black px-2 whitespace-nowrap" rowspan="2">TRIAL BALANCE</th>
                    <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">BALANCE AS OF
                        {{ Carbon\Carbon::create(month: $data['month'], year: $data['year'])->subMonthNoOverflow()->format('F Y') }}
                    </th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ-LOANS</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ-OTHERS</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ-MSO</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ-RICE</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ-LAB</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ-DORM</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CRJ</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CDJ-LOANS</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CDJ-OTHERS</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CDJ-MSO</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CDJ-RICE</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">CDJ</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">JEV</th>
                    <th class="border border-black px-2 whitespace-nowrap" colspan="2">ENDING BALANCE</th>
                </tr>
                <tr>
                    @foreach (range(1, 15) as $a)
                        <th class="border border-black px-2">DEBIT</th>
                        <th class="border border-black px-2">CREDIT</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($trial_balance_entries as $trial_balance_entry)
                    @if (
                        $trial_balance_entry->parent?->name === 'loans receivable' &&
                            $trial_balance_entry->auditable instanceof App\Models\LoanType)
                        @include('partials.loan-receivables-row-data')
                    @else
                        @include('partials.trial-balance-row-data')
                    @endif
                @endforeach
                @include('partials.trial-balance-row-footer')
            </tbody>
        </table>
    </div>
</div>
