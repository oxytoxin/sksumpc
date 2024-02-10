<div class="flex flex-col max-h-[32rem]">
    <div class="flex items-center justify-center">
        <h1 class="text-center font-bold text-xl my-2">TRIAL BALANCE REPORT</h1>
        <x-heroicon-o-arrow-path wire:loading class="w-6 h-6 animate-spin" />
    </div>
    <div class="flex-1 overflow-auto">
        <table>
            <thead>
                <tr>
                    <th class="border border-black px-2 whitespace-nowrap" rowspan="2">TRIAL BALANCE</th>
                    <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">
                        BALANCE FORWARDED
                    </th>
                    @foreach ($this->transaction_types as $transaction_type)
                        <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">
                            {{ $transaction_type->name }}
                        </th>
                    @endforeach
                    <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">
                        TOTAL
                    </th>
                    <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">
                        ENDING BALANCE
                    </th>
                </tr>
                <tr>
                    <th class="border border-black px-2">DEBIT</th>
                    <th class="border border-black px-2">CREDIT</th>
                    @foreach ($this->transaction_types as $transaction_type)
                        <th class="border border-black px-2">DEBIT</th>
                        <th class="border border-black px-2">CREDIT</th>
                    @endforeach
                    <th class="border border-black px-2">DEBIT</th>
                    <th class="border border-black px-2">CREDIT</th>
                    <th class="border border-black px-2">DEBIT</th>
                    <th class="border border-black px-2">CREDIT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->accounts as $account)
                    @include('partials.trial-balance-row-data', ['account' => $account])
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="border border-black px-2 uppercase text-lg font-bold whitespace-nowrap">
                        TOTAL
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('balance_forwarded_debit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('balance_forwarded_credit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_crj_debit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_crj_credit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_cdj_debit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_cdj_credit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_jev_debit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_jev_credit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_debit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->sum('total_credit')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->where('debit_operator', 1)->sum('ending_balance')) }}
                    </td>
                    <td class="border border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format($this->accounts->where('credit_operator', 1)->sum('ending_balance')) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
