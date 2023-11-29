@php
    use function Filament\Support\format_money;
@endphp
<div x-data class="max-w-4xl mx-auto">
    <div class="p-4 print:text-[10pt]" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 class="text-2xl text-center mt-4 font-bold">DISCLOSURE SHEET</h4>
        <div class="my-4 flex">
            <div class="w-2/3">
                <h4>NAME: <strong>{{ $loan->member->full_name }}</strong></h4>
                <h4>LOAN TYPE: <strong>{{ $loan->loan_type->name }}</strong></h4>
                <p>Priority Number:</p>
            </div>
            <div class="w-1/3 font-bold flex justify-between">
                <p>DATE:</p>
                <p>{{ $loan->transaction_date->format('F d, Y') }}</p>
            </div>
        </div>
        <div class="flex justify-between">
            <strong>AMOUNT GRANTED</strong>
            <p class="font-bold">{{ format_money($loan->gross_amount, 'PHP') }}</p>
        </div>
        <div class="px-16">
            <strong>LESS:</strong>
            <div class="px-16">
                @foreach ($loan->deductions as $deduction)
                    <div class="flex justify-between">
                        <p>{{ $deduction['name'] }}</p>
                        <p class="font-bold">{{ format_money($deduction['amount'], 'PHP') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex pl-32 justify-between">
            <p class="font-bold">TOTAL DEDUCTIONS</p>
            <p class="font-bold">{{ format_money($loan->deductions_amount, 'PHP') }}</p>
        </div>
        <hr class="border my-2 border-black">
        <div class="flex justify-between">
            <p class="font-bold">NET PROCEEDS</p>
            <p class="font-bold">{{ format_money($loan->net_amount, 'PHP') }}</p>
        </div>

        <x-app.cashier.reports.signatories :signatories="$signatories" />

    </div>
    <div class="p-4 flex justify-end space-x-2">
        <x-filament::button wire:ignore href="{{ back()->getTargetUrl() }}" outlined tag="a">Back</x-filament::button>
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
