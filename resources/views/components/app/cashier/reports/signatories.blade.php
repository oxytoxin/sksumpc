<div class="mt-4 grid grid-cols-2 gap-2 print:text-[8pt]">
    @foreach ($signatories as $signatory)
        @if ($signatory)
            <div>
                <p>{{ $signatory['action'] }}</p>
                <div class="flex justify-around">
                    <div class="flex flex-col items-center">
                        <p class="font-bold uppercase">{{ $signatory['name'] }}</p>
                        <p>{{ $signatory['designation'] }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <p>{{ config('app.transaction_date')?->format('m/d/Y') }}</p>
                        <p>Date</p>
                    </div>
                </div>
            </div>
        @else
            <div></div>
        @endif
    @endforeach
</div>
