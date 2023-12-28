<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th class="border border-black px-2 whitespace-nowrap" rowspan="2">TRIAL BALANCE</th>
                <th class="border border-black px-2 whitespace-nowrap" colspan="2">BALANCE AS OF</th>
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
                @include('partials.trial-balance-row-data')
            @endforeach
            @include('partials.trial-balance-row-footer')
        </tbody>
    </table>
</div>
