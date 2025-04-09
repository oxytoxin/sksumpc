<div class="flex max-h-[32rem] flex-col">
    <button @click="tableToExcel('report', 'trial-balance.xls')">download</button>
    <div class="flex items-center justify-center">
        <h1 class="my-2 text-center text-xl font-bold">TRIAL BALANCE REPORT</h1>
        <x-heroicon-o-arrow-path class="h-6 w-6 animate-spin" wire:loading />
    </div>
    <div class="flex-1 overflow-auto">
        <table class="w-full border-separate border-spacing-0 border border-black" id="report">
            <thead class="sticky top-0 z-[5] bg-white">
                <tr>
                    <th class="sticky left-0 whitespace-nowrap border border-black bg-white px-2" rowspan="3">TRIAL BALANCE
                    </th>
                    <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="16">{{ $this->selected_month->format('F Y') }}</th>
                </tr>
                <tr>
                    @foreach ($this->transaction_types as $transaction_type)
                        <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="2">
                            {{ $transaction_type->name }}
                        </th>
                        <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="2">
                            {{ $transaction_type->name }}-MSO
                        </th>
                    @endforeach
                    <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="2">
                        TOTAL
                    </th>
                    <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="2">
                        ENDING BALANCE
                    </th>
                </tr>
                <tr>
                    @foreach ($this->transaction_types as $transaction_type)
                        <th class="border border-black px-2">DEBIT</th>
                        <th class="border border-black px-2">CREDIT</th>
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
                @foreach ($this->trial_balance as $key => $account)
                    @include('partials.single-month-trial-balance-row-data', ['account' => $account])
                @endforeach
            </tbody>
            @include('partials.single-month-trial-balance-row-footer')
        </table>
    </div>
</div>
