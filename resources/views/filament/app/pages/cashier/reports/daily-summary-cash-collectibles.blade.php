@php
    use function Filament\Support\format_money;
    use App\Models\PaymentType;
@endphp

<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :signatories="$signatories" title="DAILY COLLECTIONS REPORT">
        <div class="space-y-4">
            @foreach ($this->cash_collectibles as $payment_type => $cash_collectible_items)
                <div>
                    <h3 class="font-bold">{{ $payment_type }} Collections</h3>
                    <div class="grid grid-cols-2">
                        @foreach ($cash_collectible_items as $item)
                            <h4 class="text-right">{{ $item['cash_collectible'] }}</h4>
                            <h4 class="text-right">{{ format_money($item['total_amount'], 'PHP') }}</h4>
                        @endforeach
                    </div>
                    <hr class="border border-black">
                    <div class="flex justify-between">
                        <h5 class="font-bold">Total {{ $payment_type }}</h5>
                        <h5 class="font-bold">{{ format_money(collect($cash_collectible_items)->sum('total_amount'), 'PHP') }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            <h3 class="font-bold text-center">SUMMARY ON DAILY COLLECTIONS REPORT</h3>
            <div class="grid grid-cols-2">
                @php
                    $total = 0;
                @endphp
                @foreach ($this->cash_collectibles as $payment_type => $cash_collectible_items)
                    <h3 class="font-bold text-right">{{ $payment_type }}:</h3>
                    <h4 class="text-right">{{ format_money(collect($cash_collectible_items)->sum('total_amount'), 'PHP') }}</h4>
                    @php
                        $total += collect($cash_collectible_items)->sum('total_amount');
                    @endphp
                @endforeach
                <h3 class="font-bold">TOTAL COLLECTIONS:</h3>
                <h4 class="text-right">{{ format_money($total, 'PHP') }}</h4>
            </div>
        </div>
    </x-app.cashier.reports.report-layout>

</x-filament-panels::page>
