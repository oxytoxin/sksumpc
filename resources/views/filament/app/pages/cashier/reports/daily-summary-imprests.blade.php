@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>
    @php
        $imprests = auth()
            ->user()
            ->cashier_imprests()
            ->with('member')
            ->whereDate('transaction_date', today())
            ->get();
    @endphp
    <div x-data class="max-w-6xl mx-auto">
        <div class="p-4" x-ref="print">
            <div class="flex justify-center mb-16">
                <div class="flex space-x-24 items-center">
                    <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-28">
                    <div class="flex flex-col items-center">
                        <p>Sultan Kudarat State University</p>
                        <p>MULTI-PURPOSER COOPERATIVE</p>
                        <p>Bo. 2, EJC Montilla, Tacurong City</p>
                    </div>
                </div>
            </div>
            <h4 class="text-xl mt-8 font-bold text-center">DAILY SUMMARY OF MEMBER'S IMPRESTS DEPOSIT AND WITHDRAWAL</h4>
            <p class="text-center">{{ today()->format('l, F d, Y') }}</p>

            <div class="my-8">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-center border-2 border-black">NO.</th>
                            <th class="text-center border-2 border-black">ACCOUNT NAME</th>
                            <th class="text-center border-2 border-black">DEPOSIT</th>
                            <th class="text-center border-2 border-black">WITHDRAWAL</th>
                            <th class="text-center border-2 border-black">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @forelse ($imprests as $imprest)
                            @php
                                $total += $imprest->amount;
                            @endphp
                            <tr>
                                <th class="text-center border-2 border-black">{{ $loop->iteration }}</th>
                                <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $imprest->member->full_name }}</td>
                                <td class="text-center border-2 border-black">{{ $imprest->deposit ? format_money($imprest->deposit, 'PHP') : '' }}</td>
                                <td class="text-center border-2 border-black">{{ $imprest->withdrawal ? format_money($imprest->withdrawal, 'PHP') : '' }}</td>
                                <td class="text-center border-2 border-black">{{ format_money($total, 'PHP') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center border-2 border-black">No transactions today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-app.cashier.reports.signatories :signatories="$signatories" />
        </div>
        <div class="p-4 flex justify-end gap-4">
            <x-filament::button color="success" tag="a" href="{{ route('filament.app.resources.members.index') }}">Back to Membership Module</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
        </div>
    </div>

</x-filament-panels::page>
