<div class="flex max-h-[32rem] flex-col">
    <div class="flex items-center justify-center">
        <h1 class="my-2 text-center text-xl font-bold">STATEMENT OF FINANCIAL OPERATION</h1>
        <x-heroicon-o-arrow-path wire:loading class="h-6 w-6 animate-spin" />
    </div>
    <div class="flex-1 overflow-auto">
        <table class="w-full border-separate border-spacing-0 border border-black">
            <thead class="sticky top-0 bg-white">
                <tr>
                    <th rowspan="2" class="border border-black px-2"></th>
                    <th colspan="2" class="border border-black px-2">CURRENT</th>
                    <th colspan="2" class="border border-black px-2">PREVIOUS</th>
                    <th rowspan="2" class="border border-black px-2">INCREASE/DECREASE</th>
                    <th rowspan="2" class="border border-black px-2">REMARKS</th>
                </tr>
                <tr>
                    <th class="border border-black px-2">DEBIT</th>
                    <th class="border border-black px-2">CREDIT</th>
                    <th class="border border-black px-2">DEBIT</th>
                    <th class="border border-black px-2">CREDIT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->account_types->whereIn('id', [3, 5]) as $account_type)
                    <tr>
                        <th colspan="7" class="whitespace-nowrap border border-black px-4 text-left">
                            {{ $account_type->name }}</th>
                    </tr>
                    @foreach ($this->accounts->where('account_type_id', $account_type->id) as $account)
                        @include('partials.financial-condition-operation-row-data', [
                            'account' => $account,
                        ])
                    @endforeach
                    @include('partials.financial-condition-operation-row-footer', [
                        'accounts' => $this->accounts->where('account_type_id', $account_type->id),
                        'account_type' => $account_type->name,
                    ])
                @endforeach
            </tbody>
        </table>
    </div>
</div>
