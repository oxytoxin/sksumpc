<x-filament-panels::page>
    {{ $this->form }}
    <div class="w-full">
        <x-app.cashier.reports.report-layout title="DAILY COLLECTIONS REPORT"
                                             :date="date_create($transaction_date ?? config('app.transaction_date'))"
                                             :signatories="$this->getSignatories()">
            <div class="flex items-start gap-16">
                <div class="w-1/2">
                    <div class="space-y-4">
                        @foreach ($this->payment_types as $payment_type)
                            <div>
                                <h3 class="font-bold uppercase">{{ $payment_type->name }} COLLECTIONS</h3>
                                <div>
                                    @foreach ($this->daily_collections[$payment_type->id] ?? [] as $daily_collection)
                                        <div class="flex justify-between">
                                            <h4>{{ $daily_collection->name }}</h4>
                                            <strong>{{ renumber_format($daily_collection->total_amount) }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                                <hr class="border border-black">
                                <div class="flex justify-between">
                                    <h3 class="uppercase">TOTAL {{ $payment_type->name }}</h3>
                                    <strong>{{ number_format(collect($this->daily_collections[$payment_type->id] ?? [])->sum('total_amount'), 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        <h3 class="font-bold">SUMMARY ON DAILY COLLECTION REPORT</h3>
                        @foreach ($this->payment_types as $payment_type)
                            <div class="flex justify-between">
                                <h3 class="uppercase">{{ $payment_type->name }}</h3>
                                <strong>{{ number_format(collect($this->daily_collections[$payment_type->id] ?? [])->sum('total_amount'), 2) }}</strong>
                            </div>
                        @endforeach
                        <hr class="border border-black">
                        <div class="flex justify-between">
                            <h3 class="font-bold uppercase">TOTAL COLLECTIONS</h3>
                            <strong>{{ number_format(collect($this->daily_collections)->flatten()->sum('total_amount'), 2) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex w-1/2 flex-col items-center border-2 border-blue-700 p-4">
                    <h3 class="font-bold">CASH DEPOSITS</h3>
                    <h4>{{ date_create($transaction_date)?->format('m/d/Y') }}</h4>
                    <div class="mt-8 w-2/3">
                        <div class="flex justify-between">
                            <h4>GEN FUND</h4>
                            <h4>{{ renumber_format($this->general_fund_deposits) }}</h4>
                        </div>
                        <div class="flex justify-between">
                            <h4>MSO FUND</h4>
                            <h4>{{ renumber_format($this->mso_deposits) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </x-app.cashier.reports.report-layout>
    </div>
</x-filament-panels::page>
