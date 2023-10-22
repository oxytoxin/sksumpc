@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4" x-ref="print">
        <div class="flex justify-center mb-16">
            <div class="flex space-x-24 items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-32">
                <div class="flex flex-col items-center">
                    <p>Sultan Kudarat State University</p>
                    <p>MULTI-PURPOSER COOPERATIVE</p>
                    <p>Bo. 2, EJC Montilla, Tacurong City</p>
                    <p>CDA Reg. No.: 9520-12000926</p>
                    <div class="flex space-x-12">
                        <p>CIN: 0103120093</p>
                        <p>TIN: 005-811-330</p>
                    </div>
                    <p>Contact No: 09557966507/email address: sksu.mpc@gmail.com</p>
                    <h4 class="text-3xl mt-8 font-bold">SUBSIDIARY LEDGER FOR CBU</h4>
                </div>
            </div>
        </div>
        <div class="my-4">
            <h4>Name: {{ $member->full_name }}</h4>
            <h4>Campus: {{ $member->division?->name }}</h4>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center border-2 border-black">DATE</th>
                    <th class="text-center border-2 border-black">REF. #</th>
                    <th class="text-center border-2 border-black px-6">DR</th>
                    <th class="text-center border-2 border-black">CR</th>
                    <th class="text-center border-2 border-black">OUTSTANDING BALANCE</th>
                    <th class="text-center border-2 border-black px-4">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($this->table->getRecords() as $record)
                    <tr>
                        <th class="text-left px-4 border-2 border-black">{{ $record->transaction_date->format('m/d/Y') }}</th>
                        <td class="text-left px-4 border-2 border-black">{{ $record->reference_number }}</td>
                        <td class="text-center border-2 border-black"></td>
                        <td class="text-right px-4 border-2 border-black">{{ format_money($record->amount, 'PHP') }}</td>
                        @php
                            $total += $record->amount;
                        @endphp
                        <td class="text-right px-4 border-2 border-black">{{ format_money($total, 'PHP') }}</td>
                        <td class="text-center border-2 border-black">{{ $record->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
