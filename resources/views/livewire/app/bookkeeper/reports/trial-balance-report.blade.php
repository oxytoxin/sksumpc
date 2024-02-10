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
                    @foreach ($this->transaction_types as $transaction_type)
                        <th class="border border-black px-2 whitespace-nowrap uppercase" colspan="2">
                            {{ $transaction_type->name }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($this->transaction_types as $transaction_type)
                        <th class="border border-black px-2">DEBIT</th>
                        <th class="border border-black px-2">CREDIT</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($this->accounts as $account)
                    @include('partials.trial-balance-row-data', ['account' => $account])
                @endforeach
            </tbody>
        </table>
    </div>
</div>
