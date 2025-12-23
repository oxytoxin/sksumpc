@props(['title', 'signatories', 'hasHeader' => true, 'date' => null])
<div class="mx-auto max-w-7xl" x-data>
    <div class="p-4" x-ref="print">
        @if ($hasHeader)
            <x-app.cashier.reports.report-heading/>
        @endif
        @isset($title)
            <h4 class="mt-4 text-center text-xl font-bold" wire:ignore>{{ $title }}</h4>
            <p class="text-center font-bold">{{ ($date ?? config('app.transaction_date') ?? today())->format('l, F d, Y') }}</p>
        @endisset

        <div class="my-4 text-sm print:text-[10pt]">
            {{ $slot }}
        </div>
        @isset($signatories)
            <x-app.cashier.reports.signatories :signatories="$signatories"/>
        @endisset
    </div>
    <div class="flex justify-end gap-4 p-4">
        @isset($buttons)
            {{ $buttons }}
        @else
            <x-filament::button href="{{ back()->getTargetUrl() }}" wire:ignore color="success" tag="a">
                Previous Page
            </x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, '')">
                Print
            </x-filament::button>
        @endisset
    </div>
</div>
