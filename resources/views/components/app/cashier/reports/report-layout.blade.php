@props(['title'])
<div x-data class="max-w-6xl mx-auto">
    <div class="p-4" x-ref="print">
        <div class="flex justify-center mb-4">
            <div class="flex space-x-24 items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-28">
                <div class="flex flex-col items-center">
                    <p>Sultan Kudarat State University</p>
                    <p>MULTI-PURPOSER COOPERATIVE</p>
                    <p>Bo. 2, EJC Montilla, Tacurong City</p>
                </div>
            </div>
        </div>
        <h4 contenteditable wire:ignore class="text-xl mt-4 font-bold text-center">{{ $title }}</h4>
        <p class="text-center">{{ today()->format('l, F d, Y') }}</p>

        <div class="my-4">
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
