<div class="mt-8 grid grid-cols-2 gap-4">
    @foreach ($signatories as $signatory)
        @if ($signatory)
            <div>
                <p>{{ $signatory['action'] }}</p>
                <div class="flex justify-around">
                    <div class="flex flex-col items-center mt-8">
                        <p class="font-bold uppercase">{{ $signatory['name'] }}</p>
                        <p>{{ $signatory['position'] }}</p>
                    </div>
                    <div class="flex flex-col items-center mt-8">
                        <p>{{ today()->format('m/d/Y') }}</p>
                        <p>Date</p>
                    </div>
                </div>
            </div>
        @else
            <div></div>
        @endif
    @endforeach
</div>
