<div x-data>
    <div x-ref="print" class="flex max-h-[32rem] flex-col">
        <div class="hidden print:block">
            <x-app.cashier.reports.report-heading />
        </div>
        <div class="flex items-center justify-center">
            <h1 class="my-2 text-center text-xl font-bold">STATEMENT OF FINANCIAL OPERATION</h1>
            <div class="print:hidden">
                <x-heroicon-o-arrow-path wire:loading class="h-6 w-6 animate-spin" />
            </div>
        </div>
        <div class="flex-1 overflow-auto">
            <table class="w-full border-separate border-spacing-0 border border-black">
                <thead class="sticky top-0 bg-white">
                    <tr>
                        <th rowspan="2" class="border border-black px-2"></th>
                        <th colspan="2" class="whitespace-nowrap border border-black px-2">{{ $this->selected_month->format('F Y') }}</th>
                        <th rowspan="2" class="border border-black px-2">REMARKS</th>
                    </tr>
                    <tr>
                        <th class="border border-black px-2">DEBIT</th>
                        <th class="border border-black px-2">CREDIT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->account_types->whereIn('id', [3, 5]) as $account_type)
                        <tr>
                            <th colspan="4" class="whitespace-nowrap border border-black bg-green-300 px-4 text-left">
                                {{ $account_type->name }}</th>
                        </tr>
                        @foreach ($this->trial_balance->where('account_type_id', $account_type->id) as $account)
                            @include('partials.single-month-financial-condition-operation-row-data', [
                                'account' => $account,
                            ])
                        @endforeach
                        @include('partials.single-month-financial-condition-operation-row-footer', [
                            'accounts' => $this->trial_balance->where('account_type_id', $account_type->id),
                            'account_type' => $account_type,
                        ])
                    @endforeach
                    @include('partials.single-month-financial-operation-extras')
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Statement of Financial Operation')">Print</x-filament::button>
    </div>
</div>
