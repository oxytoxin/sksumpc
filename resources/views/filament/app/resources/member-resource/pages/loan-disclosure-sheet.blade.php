@php
    use function Filament\Support\format_money;
@endphp
<div x-data class="max-w-4xl mx-auto">
    <div class="p-4 print:text-[10pt] text-sm" x-ref="print">
        <div>
            <x-app.cashier.reports.report-heading />
            <h4 class="text-2xl text-center mt-4 font-bold">DISCLOSURE SHEET</h4>
            <div class="my-4 flex">
                <div class="w-2/3">
                    <h4>ACCOUNT NUMBER: <strong>{{ $loan->loan_account->number }}</strong></h4>
                    <h4>NAME: <strong>{{ $loan->member->full_name }}</strong></h4>
                    <h4>LOAN TYPE: <strong>{{ $loan->loan_type->name }}</strong></h4>
                    <p>PRIORITY NUMBER: {{ $loan->priority_number }}</p>
                </div>
                <div class="w-1/3 font-bold flex justify-between">
                    <p>DATE:</p>
                    <p>{{ $loan->transaction_date->format('F d, Y') }}</p>
                </div>
            </div>
            <div class="flex justify-between">
                <strong>AMOUNT GRANTED</strong>
                <p class="font-bold">{{ format_money($loan->gross_amount, 'PHP') }}</p>
            </div>
            <div class="my-4">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="border border-black">NAME</th>
                            <th class="border border-black">DEBIT</th>
                            <th class="border border-black">CREDIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->disclosure_sheet_items as $disclosure_sheet_item)
                            <tr>
                                <td class="border border-black px-4">{{ $disclosure_sheet_item['name'] ?? '' }}</td>
                                <td class="border border-black text-right px-4 w-1/6">
                                    {{ renumber_format($disclosure_sheet_item['debit']) }}
                                </td>
                                <td class="border border-black text-right px-4 w-1/6">
                                    {{ renumber_format($disclosure_sheet_item['credit']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between">
                <p class="font-bold">TOTAL DEDUCTIONS</p>
                <p class="font-bold">{{ format_money($loan->deductions_amount, 'PHP') }}</p>
            </div>
            <hr class="border my-2 border-black">
            <div class="flex justify-between">
                <p class="font-bold">NET PROCEEDS</p>
                <p class="font-bold">{{ format_money($loan->net_amount, 'PHP') }}</p>
            </div>
        </div>
        <div class="break-before-page">
            <div class="hidden print:block">
                <x-app.cashier.reports.report-heading />
            </div>
            <h1 class="text-center font-bold my-8">PROMISSORY NOTE</h1>
            <div class="flex mb-4">
                <div class="flex-1">
                    <p>
                        P <span class="border-b border-black px-8">{{ renumber_format($loan->gross_amount, 2) }}</span>
                    </p>
                </div>
                <div class="flex-1">
                    <p>Priority Number: <span>{{ $loan->priority_number }}</span></p>
                    <p>Authenticated by: <span>&nbsp;</span></p>
                </div>

            </div>
            <div class="flex-col flex gap-y-4">
                <p class="indent-8">
                    For the value received, I/We jointly and severally, promise to pay the SKSU - MPC or order the sum
                    of <span class="uppercase px-4 border-b border-black">{{ $loan->net_amount_in_words }} pesos
                        only</span>
                    (P <span class="border-b border-black px-4">(P {{ renumber_format($loan->net_amount, 2) }})</span>)
                    payable in <span class="px-4 border-b border-black">{{ $loan->number_of_terms }} months</span>
                    equal installment of (P <span class="border-b border-black px-4">(P
                        {{ renumber_format($loan->monthly_payment, 2) }})</span>) the first payment to be made on <span
                        class="px-4 border-b border-black">{{ $loan->transaction_date->addMonthNoOverflow()->format('F d, Y') }}</span>
                    and every payday thereafter
                    until the full amount has been paid.
                </p>
                <p class="indent-8">Collateral: Monthly/Semi-monthly salary PAYROLL DEDUCTION</p>
                <p class="indent-8">
                    In case of any default in payments as herein agreed, the entire balance of this note shall become
                    immediately due and payable.
                </p>
                <p class="indent-8">
                    It is further agreed that in case payment shall not be made at maturity, I/We will pay the penalty
                    of 1% per month of the principal balance and the interest due this note starting from
                    <span class="border-b border-black px-4">
                        {{ $loan->transaction_date->addMonthsNoOverflow($loan->number_of_terms)->addDay()->format('F d, Y') }}
                    </span>
                </p>
                <p class="indent-8">
                    In case of judicial action of this obligation or any part of it, the debtor waives all his rights
                    under the provision of Rule 3, Section 13 and Rule 39, Section 12 of the Rules of Court.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-x-32 gap-y-12 mt-12">
                <div>
                    <p class="uppercase border-b border-black text-center">
                        {{ $loan->disbursement_voucher?->address }}&nbsp;
                    </p>
                    <p class="text-center">Address</p>
                </div>
                <div>
                    <p class="uppercase border-b border-black text-center">{{ $loan->member->full_name }}</p>
                    <p class="text-center">( Signature over Printed Name of Borrower)</p>
                </div>
                @foreach ($loan->loan_application->comakers as $comaker)
                    <div>
                        <p class="uppercase border-b border-black text-center">{{ $comaker }}</p>
                        <p class="text-center">( Signature over Printed Name of Co-Borrower)</p>
                    </div>
                @endforeach
            </div>
        </div>
        <x-app.cashier.reports.signatories :signatories="$signatories" />

    </div>
    <div class="p-4 flex justify-end space-x-2">
        <x-filament::button wire:ignore href="{{ back()->getTargetUrl() }}" outlined
            tag="a">Back</x-filament::button>
        <x-filament::button icon="heroicon-o-printer"
            @click="printOut($refs.print.outerHTML, 'CBU Subsidiary Ledger')">Print</x-filament::button>
    </div>
</div>
