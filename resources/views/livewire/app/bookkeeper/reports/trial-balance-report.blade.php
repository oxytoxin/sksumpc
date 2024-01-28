<div class="flex flex-col max-h-[32rem]">
    <h1 class="text-center font-bold text-xl my-2">TRIAL BALANCE REPORT</h1>
    <div class="flex-1 overflow-auto">
        <table>
            <thead>
                <tr>
                    <th class="border border-black px-2 whitespace-nowrap" rowspan="2">TRIAL BALANCE</th>
                    @foreach ($this->trial_balance_header_columns as $column)
                        <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">
                            {{ $column }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($this->trial_balance_header_columns as $column)
                        <th class="border border-black px-2">DEBIT</th>
                        <th class="border border-black px-2">CREDIT</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($this->trial_balance_entries as $trial_balance_entry)
                    @include('partials.trial-balance-row-data')
                @endforeach
            </tbody>
        </table>
    </div>
</div>
