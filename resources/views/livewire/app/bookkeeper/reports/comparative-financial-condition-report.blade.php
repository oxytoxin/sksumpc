<div  x-data>
    <div x-ref="print" class="flex max-h-[32rem] flex-col print:text-[10pt]">
        <div class="hidden print:block">
            <x-app.cashier.reports.report-heading />
        </div>
        <div class="flex items-center justify-center">
            <h1 class="my-2 text-center text-xl font-bold">STATEMENT OF FINANCIAL CONDITION</h1>
            <div class="print:hidden">
                <x-heroicon-o-arrow-path wire:loading class="h-6 w-6 animate-spin" />
            </div>
        </div>
        <div class="flex-1 overflow-auto">
            <table class="w-full border-separate border-spacing-0 border border-black">
                <thead class="sticky top-0 bg-white">
                    <tr>
                        <th class="print:text-[8pt] border border-black px-2"></th>
                        <th class="print:text-[8pt] whitespace-nowrap border border-black px-2">{{ $this->to_date->format('F d, Y') }}</th>
                        <th class="print:text-[8pt] whitespace-nowrap border border-black px-2">{{ $this->from_date->format('F d, Y') }}</th>
                        <th class="print:text-[8pt] border border-black px-2">INCREASE /DECREASE</th>
                        <th class="print:text-[8pt] whitespace-nowrap border border-black px-2">{{ $this->formatted_balance_forwarded_date }}</th>
                        <th class="print:text-[8pt] border border-black px-2">INCREASE /DECREASE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->account_types->whereIn('id', [1, 2, 4]) as $account_type)
                        <tr>
                            <th colspan="11" class="print:text-[8pt] whitespace-nowrap border border-black bg-green-300 px-4 text-left">{{ $account_type->name }}</th>
                        </tr>
                        @if ($account_type->id == 4)
                            @include('partials.comparative-financial-condition-equity-extras')
                        @endif
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
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Statement of Financial Condition')">Print</x-filament::button>
    </div>
</div>