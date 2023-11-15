@php
    use function Filament\Support\format_money;
@endphp
<div x-data>
    <div class="p-4 print:text-[10pt] print:leading-tight print:w-full" x-ref="print">
        <div class="flex justify-center mb-8">
            <div class="flex space-x-24 items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-32 print:h-16">
                <div class="flex flex-col items-center print:text-[9pt] print:leading-none">
                    <strong class="print:text-[11pt]">SULTAN KUDARAT STATE UNIVERSITY - MULTI-PURPOSE COOPERATIVE</strong>
                    <strong class="print:text-[11pt]">(SKSU-MPC)</strong>
                    <p>Bo. 2, EJC Montilla, Tacurong City</p>
                    <p>CDA Reg. No.: 9520-12000926 / CIN: 0103120093 / TIN: 005-811-330</p>
                    <p>Contact No: 0906-826-1905 or 0966-702-9200</p>
                    <p>Email Address: sksu.mpc@gmail.com</p>
                </div>
            </div>
        </div>
        <h4 class="text-3xl text-center mt-4 print:text-[14pt] font-bold">SUBSIDIARY LEDGER FOR CBU</h4>
        <div class="my-4">
            <h4>Name: {{ $member->full_name }}</h4>
            <h4>Campus: {{ $member->division?->name }}</h4>
        </div>
        <table class="w-full print:text-[10pt]">
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
                        <td class="text-right px-4 border-2 border-black">{{ number_format($record->amount, 2) }}</td>
                        @php
                            $total += $record->amount;
                        @endphp
                        <td class="text-right px-4 border-2 border-black">{{ number_format($total, 2) }}</td>
                        <td class="text-center border-2 border-black">{{ $record->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="p-4 flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
