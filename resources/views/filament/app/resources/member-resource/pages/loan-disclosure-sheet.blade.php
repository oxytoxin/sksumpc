@php
    use function Filament\Support\format_money;
@endphp
<x-filament-panels::page>
    <div class="mx-auto max-w-4xl" x-data>
        <div class="p-4 text-sm print:text-[8pt]" x-ref="print">
            <div>
                <x-app.cashier.reports.report-heading />
                <h4 class="mt-2 text-center text-2xl font-bold print:text-[12pt]">DISCLOSURE SHEET</h4>
                <div class="my-2 flex">
                    <div class="w-2/3">
                        <h4>ACCOUNT NUMBER: <strong>{{ $loan->loan_account->number }}</strong></h4>
                        <h4>NAME: <strong>{{ $loan->member->full_name }}</strong></h4>
                        <h4>LOAN TYPE: <strong>{{ $loan->loan_type->name }}</strong></h4>
                        <p>PRIORITY NUMBER: {{ $loan->priority_number }}</p>
                    </div>
                    <div class="flex w-1/3 justify-between font-bold">
                        <p>DATE:</p>
                        <p>{{ $loan->transaction_date->format('F d, Y') }}</p>
                    </div>
                </div>
                <div class="flex justify-between px-4">
                    <strong>AMOUNT GRANTED</strong>
                    <p class="font-bold">{{ format_money($loan->gross_amount, 'PHP') }}</p>
                </div>
                <div class="my-2">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border-x border-black px-4 text-left">NAME</th>
                                <th class="border-x border-black px-4 text-right">DEBIT</th>
                                <th class="border-x border-black px-4 text-right">CREDIT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loan->disclosure_sheet_items as $disclosure_sheet_item)
                                <tr>
                                    <td class="border-x border-black px-4 print:text-[8pt]">{{ $disclosure_sheet_item['name'] ?? '' }}</td>
                                    <td class="w-1/6 border-x border-black px-4 text-right print:text-[8pt]">
                                        {{ renumber_format($disclosure_sheet_item['debit']) }}
                                    </td>
                                    <td class="w-1/6 border-x border-black px-4 text-right print:text-[8pt]">
                                        {{ renumber_format($disclosure_sheet_item['credit']) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between px-4">
                    <p class="font-bold">TOTAL DEDUCTIONS</p>
                    <p class="font-bold">{{ format_money($loan->deductions_amount, 'PHP') }}</p>
                </div>
                <div class="flex justify-between px-4">
                    <p class="font-bold">NET PROCEEDS</p>
                    <p class="font-bold">{{ format_money($loan->net_amount, 'PHP') }}</p>
                </div>
            </div>
            <x-app.cashier.reports.signatories :signatories="$this->getSignatories()" />
        </div>
        <div class="flex justify-end space-x-2 p-4">
            <x-filament::button href="{{ back()->getTargetUrl() }}" wire:ignore outlined tag="a">Back</x-filament::button>
            <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
