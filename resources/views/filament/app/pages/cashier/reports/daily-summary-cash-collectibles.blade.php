@php
    use function Filament\Support\format_money;
    use App\Models\PaymentType;
@endphp

<x-filament-panels::page>
    @php
        $cash_collectibles = auth()
            ->user()
            ->cashier_cash_collectible_payments()
            ->whereDate('transaction_date', today())
            ->join('payment_types', 'cash_collectible_payments.payment_type_id', '=', 'payment_types.id')
            ->join('cash_collectibles', 'cash_collectible_payments.cash_collectible_id', '=', 'cash_collectibles.id')
            ->groupBy(['payment_type_id', 'cash_collectible_id', 'payment_types.name', 'cash_collectibles.name'])
            ->selectRaw('payment_type_id, cash_collectible_id, cash_collectibles.name as cash_collectible, sum(amount) as total_amount, payment_types.name as payment_type')
            ->get()
            ->groupBy('payment_type');
    @endphp

    <x-app.cashier.reports.report-layout title="DAILY COLLECTIONS REPORT">
        <div class="space-y-4">
            @foreach ($cash_collectibles as $payment_type => $cash_collectible_items)
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
                @foreach ($cash_collectibles as $payment_type => $cash_collectible_items)
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
        <x-slot:signatories>
            <x-app.cashier.reports.signatories :signatories="$signatories" />
        </x-slot:signatories>
        <x-slot:buttons>
            <x-filament::button color="success" tag="a" href="{{ back()->getTargetUrl() }}">Previous Page</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Daily Summary Time Deposits')">Print</x-filament::button>
        </x-slot:buttons>
    </x-app.cashier.reports.report-layout>

</x-filament-panels::page>
