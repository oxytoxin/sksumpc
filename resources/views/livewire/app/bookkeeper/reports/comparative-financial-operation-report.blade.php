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
        <div class="flex-1 overflow-auto print:overflow-x-hidden">
            <table class="w-full border-separate border-spacing-0 border border-black print:w-full">
                <thead class="sticky top-0 bg-white">
                    <tr>
                        <th class="border border-black px-2"></th>
                        <th class="whitespace-nowrap border border-black px-2 print:text-[8pt]">{{ $this->to_date->format('F d, Y') }}</th>
                        <th class="whitespace-nowrap border border-black px-2 print:text-[8pt]">{{ $this->from_date->format('F d, Y') }}</th>
                        <th class="border border-black px-2 print:text-[8pt]">INCREASE /DECREASE</th>
                        <th class="whitespace-nowrap border border-black px-2 print:text-[8pt]">{{ $this->formatted_balance_forwarded_date }}</th>
                        <th class="border border-black px-2 print:text-[8pt]">INCREASE /DECREASE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->account_types->whereIn('id', [3, 5]) as $account_type)
                        <tr>
                            <th colspan="11" class="whitespace-nowrap border border-black bg-green-300 px-4 text-left print:text-[8pt]">{{ $account_type->name }}</th>
                        </tr>
                        @foreach ($this->trial_balance->where('account_type_id', $account_type->id) as $account)
                            @include('partials.comparative-financial-condition-operation-row-data', [
                                'account' => $account,
                                'account_type' => $account_type,
                            ])
                        @endforeach
                        @include('partials.comparative-financial-condition-operation-row-footer', [
                            'accounts' => $this->trial_balance->where('account_type_id', $account_type->id),
                            'account_type' => $account_type,
                        ])
                    @endforeach
                    @include('partials.comparative-financial-operation-extras')
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Statement of Financial Operation')">Print</x-filament::button>
    </div>
</div>
