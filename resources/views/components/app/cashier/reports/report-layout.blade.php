@props(['title', 'signatories'])
<div x-data class="max-w-6xl mx-auto">
    <div class="p-4" x-ref="print">
        <x-app.cashier.reports.report-heading />
        @isset($title)
            <h4 wire:ignore class="text-xl mt-4 font-bold text-center">{{ $title }}</h4>
            <p class="text-center font-bold">{{ today()->format('l, F d, Y') }}</p>
        @endisset

        <div class="my-4 print:text-[10pt]">
            {{ $slot }}
        </div>
        @isset($signatories)
            <x-app.cashier.reports.signatories :signatories="$signatories" />
        @endisset
    </div>
    <div class="p-4 flex justify-end gap-4">
        @isset($buttons)
            {{ $buttons }}
        @else
            <x-filament::button color="success" tag="a" href="{{ back()->getTargetUrl() }}">Previous Page</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, '')">Print</x-filament::button>
        @endisset
    </div>
</div>
