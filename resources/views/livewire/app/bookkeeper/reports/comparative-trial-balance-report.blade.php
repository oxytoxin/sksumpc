<div wire:init="loadData" class="flex max-h-[32rem] flex-col">
    <div class="flex items-center justify-center">
        <h1 class="my-2 text-center text-xl font-bold">TRIAL BALANCE REPORT</h1>
        <x-heroicon-o-arrow-path wire:loading class="h-6 w-6 animate-spin" />
    </div>
    <div class="flex-1 overflow-auto">
        <table class="w-full border-separate border-spacing-0 border border-black">
            <thead class="sticky top-0 z-[5] bg-white">
                <tr>
                    <th class="sticky left-0 whitespace-nowrap border border-black bg-white px-2" rowspan="3">TRIAL BALANCE</th>
                    <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="2">{{ $this->formatted_balance_forwarded_date }}</th>
                    @foreach ($this->comparative_dates as $date)
                        <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="16">{{ $date->format('F d, Y') }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th class="whitespace-nowrap border border-black px-2 uppercase" colspan="2">
                        BALANCE FORWARDED
                    </th>

                    @foreach ($this->comparative_dates as $date)
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
                    @endforeach

                </tr>
                <tr>
                    <th class="border border-black px-2">DEBIT</th>
                    <th class="border border-black px-2">CREDIT</th>
                    @foreach ($this->comparative_dates as $monthly_balance)
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
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($this->trial_balance as $key => $account)
                    @include('partials.comparative-trial-balance-row-data', ['account' => $account])
                @endforeach
            </tbody>
            @include('partials.comparative-trial-balance-row-footer')
        </table>
    </div>
</div>
