<div class="flex flex-col max-h-[32rem]">
    <div class="flex items-center justify-center">
        <h1 class="text-center font-bold text-xl my-2">TRIAL BALANCE REPORT</h1>
        <x-heroicon-o-arrow-path wire:loading class="w-6 h-6 animate-spin" />
    </div>
    <div class="flex-1 overflow-auto">
        <table class="border-separate w-full border-spacing-0 border border-black">
            <thead class="sticky top-0 bg-white">
                <tr>
                    <th class="border border-black px-2 whitespace-nowrap" rowspan="2">TRIAL BALANCE
                    </th>
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
            @include('partials.trial-balance-row-footer')
        </table>
    </div>
</div>
