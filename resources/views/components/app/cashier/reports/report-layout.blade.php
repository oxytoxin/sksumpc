@props(['title'])
<div x-data class="max-w-6xl mx-auto">
    <div class="p-4" x-ref="print">
        <x-app.cashier.reports.report-heading />
        <h4 wire:ignore class="text-xl mt-4 font-bold text-center">{{ $title }}</h4>
        <p class="text-center">{{ today()->format('l, F d, Y') }}</p>

        <div class="my-4 print:text-[10pt]">
            {{ $slot }}
        </div>
        @isset($signatories)
            {{ $signatories }}
        @endisset
    </div>
    <div class="p-4 flex justify-end gap-4">
        @isset($buttons)
            {{ $buttons }}
        @endisset
    </div>
</div>
