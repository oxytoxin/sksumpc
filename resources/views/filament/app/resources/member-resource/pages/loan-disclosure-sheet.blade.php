@php
    use function Filament\Support\format_money;
@endphp
<div x-data class="mx-auto max-w-4xl">
    <div class="p-4 text-sm print:text-[8pt]" x-ref="print">
        <div>
            <x-app.cashier.reports.report-heading />
            <h4 class="mt-2 text-center text-2xl print:text-[12pt] font-bold">DISCLOSURE SHEET</h4>
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
            <div class="flex px-4 justify-between">
                <strong>AMOUNT GRANTED</strong>
                <p class="font-bold">{{ format_money($loan->gross_amount, 'PHP') }}</p>
            </div>
            <div class="my-2">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="border-x text-left px-4 border-black">NAME</th>
                            <th class="border-x border-black text-right px-4">DEBIT</th>
                            <th class="border-x border-black text-right px-4">CREDIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->disclosure_sheet_items as $disclosure_sheet_item)
                            <tr>
                                <td class="border-x  print:text-[8pt] border-black px-4">{{ $disclosure_sheet_item['name'] ?? '' }}</td>
                                <td class="w-1/6 border-x  print:text-[8pt] border-black px-4 text-right">
                                    {{ renumber_format($disclosure_sheet_item['debit']) }}
                                </td>
                                <td class="w-1/6 border-x  print:text-[8pt] border-black px-4 text-right">
                                    {{ renumber_format($disclosure_sheet_item['credit']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex px-4 justify-between">
                <p class="font-bold">TOTAL DEDUCTIONS</p>
                <p class="font-bold">{{ format_money($loan->deductions_amount, 'PHP') }}</p>
            </div>
            <div class="flex px-4 justify-between">
                <p class="font-bold">NET PROCEEDS</p>
                <p class="font-bold">{{ format_money($loan->net_amount, 'PHP') }}</p>
            </div>
        </div>
        <x-app.cashier.reports.signatories :signatories="$signatories" />
    </div>
    <div class="flex justify-end space-x-2 p-4">
        <x-filament::button wire:ignore href="{{ back()->getTargetUrl() }}" outlined tag="a">Back</x-filament::button>
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
