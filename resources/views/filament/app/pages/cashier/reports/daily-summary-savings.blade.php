@php
    use function Filament\Support\format_money;
@endphp

<x-filament-panels::page>

    @php
        $savings = auth()
            ->user()
            ->cashier_savings()
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
            <h4 class="text-xl mt-8 font-bold text-center">DAILY SUMMARY OF MEMBER'S SAVINGS DEPOSIT AND WITHDRAWAL</h4>
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
                        @forelse ($savings as $saving)
                            @php
                                $total += $saving->amount;
                            @endphp
                            <tr>
                                <th class="text-center border-2 border-black">{{ $loop->iteration }}</th>
                                <td class="text-center border-2 border-black whitespace-nowrap px-2">{{ $saving->member->full_name }}</td>
                                <td class="text-center border-2 border-black">{{ $saving->deposit ? format_money($saving->deposit, 'PHP') : '' }}</td>
                                <td class="text-center border-2 border-black">{{ $saving->withdrawal ? format_money($saving->withdrawal, 'PHP') : '' }}</td>
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

            <div class="mt-16 grid grid-cols-2 gap-4">
                <div>
                    <p>Prepared by:</p>
                    <div class="flex justify-around">
                        <div class="flex flex-col items-center mt-8">
                            <p class="font-bold uppercase">{{ auth()->user()->name }}</p>
                            <p>Teller/Cashier</p>
                        </div>
                        <div class="flex flex-col items-center mt-8">
                            <p>{{ today()->format('m/d/Y') }}</p>
                            <p>Date</p>
                        </div>
                    </div>
                </div>

                <div>
                    <p>Checked by:</p>
                    <div class="flex justify-around">
                        <div class="flex flex-col items-center mt-8">
                            <p class="font-bold uppercase">ADRIAN VOLTAIRE POLO</p>
                            <p>Posting Clerk</p>
                        </div>
                        <div class="flex flex-col items-center mt-8">
                            <p>{{ today()->format('m/d/Y') }}</p>
                            <p>Date</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p>Received by:</p>
                    <div class="flex justify-around">
                        <div class="flex flex-col items-center mt-8">
                            <p class="font-bold uppercase">DESIREE G. LEGASPI</p>
                            <p>Treasurer</p>
                        </div>
                        <div class="flex flex-col items-center mt-8">
                            <p>{{ today()->format('m/d/Y') }}</p>
                            <p>Date</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p>Noted:</p>
                    <div class="flex justify-around">
                        <div class="flex flex-col items-center mt-8">
                            <p class="font-bold uppercase">FLORA C. DAMANDAMAN</p>
                            <p>Manager</p>
                        </div>
                        <div class="flex flex-col items-center mt-8">
                            <p>{{ today()->format('m/d/Y') }}</p>
                            <p>Date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 flex justify-end">
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
        </div>
    </div>

</x-filament-panels::page>
