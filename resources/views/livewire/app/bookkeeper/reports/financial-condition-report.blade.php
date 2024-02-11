<div class="flex flex-col max-h-[32rem]">
    <div class="flex items-center justify-center">
        <h1 class="text-center font-bold text-xl my-2">STATEMENT OF FINANCIAL OPERATION</h1>
        <x-heroicon-o-arrow-path wire:loading class="w-6 h-6 animate-spin" />
    </div>
    <div class="flex-1 overflow-auto">
        <table class="border-separate w-full border-spacing-0 border border-black">
            <thead class="sticky top-0 bg-white">

                <tr>
                    <th class="border border-black px-2"></th>
                    <th class="border border-black px-2">CURRENT</th>
                    <th class="border border-black px-2">PREVIOUS</th>
                    <th class="border border-black px-2">INCREASE/DECREASE</th>
                    <th class="border border-black px-2">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="5" class="border border-black text-left px-4 whitespace-nowrap">ASSETS</th>
                </tr>
                @foreach ($this->accounts->where('account_type_id', 1) as $account)
                    @include('partials.financial-condition-operation-row-data', ['account' => $account])
                @endforeach
                <tr class="hover:bg-green-100">
                    <td
                        class="border hover:bg-green-300 border-black px-2 uppercase text-lg font-bold whitespace-nowrap">
                        TOTAL ASSETS
                    </td>
                    <td
                        class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format(sum_no_children_recursive($this->accounts->where('account_type_id', 1), 'ending_balance')) }}
                    </td>
                    <td
                        class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ renumber_format(sum_no_children_recursive($this->accounts->where('account_type_id', 1), 'balance_forwarded_debit')) }}
                    </td>
                    <td
                        class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                        {{ format_percentage(sum_no_children_recursive($this->accounts->where('account_type_id', 1), 'ending_balance'), sum_no_children_recursive($this->accounts->where('account_type_id', 1), 'balance_forwarded_debit')) }}
                    </td>
                    <td
                        class="border hover:bg-green-300 border-black px-2 uppercase text-xs text-right whitespace-nowrap">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
