<div x-data>
    <table x-ref="print" class="w-full border-2 border-black font-serif">
        <thead>
            <tr>
                <td colspan="6">&nbsp</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">SULTAN KUDARAT STATE UNIVERSITY MULTI - PURPOSE COOPERATIVE</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">ACCESS E.J.C. Montilla, Tacurong City</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-sm font-bold">TIN 005-811-330 NON - VAT</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center text-2xl font-bold">DISBURSEMENT VOUCHER</td>
            </tr>
        </thead>
        <tbody class="mt-4 text-xs">
            <tr class="border-x-2 border-t-2 border-black">
                <td class="w-1/6 border-black px-2">PAID TO:</td>
                <td colspan="3" class="border-r-2 border-t border-black px-2">{{ $disbursement_voucher->name }}</td>
                <td class="w-1/6 border-t border-black px-2">VOUCHER:</td>
                <td colspan="1" class="border-t border-black px-2">{{ $disbursement_voucher->reference_number }}</td>
            </tr>
            <tr class="border-x-2 border-b-2 border-black">
                <td class="w-1/6 border-t border-black px-2">ADDRESS/STATION:</td>
                <td colspan="3" class="border-r-2 border-t border-black px-2">{{ $disbursement_voucher->address }}</td>
                <td class="w-1/6 border-t border-black px-2">DATE:</td>
                <td colspan="1" class="border-t border-black px-2">{{ $disbursement_voucher->transaction_date->format('m/d/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="border border-black text-center">ACCOUNT NAME</td>
                <td colspan="2" class="border border-black text-center">ACCT. CODE</td>
                <td class="border border-black text-center">DEBIT</td>
                <td class="border border-black text-center">CREDIT</td>
            </tr>
            @php
            $disbursement_voucher_items = $disbursement_voucher->disbursement_voucher_items()->with('account')->get();
            @endphp
            @foreach ($disbursement_voucher_items as $item)
            <tr class="mt-2">
                <td colspan="2" class="w-1/2 border-x border-black px-2 text-left uppercase">
                    {{ $item->account->fullname }}
                </td>
                <td colspan="2" class="w-1/6 border-x border-black text-center">{{ $item->account->number }}
                </td>
                <td class="w-1/6 border-x border-black text-right">
                    {{ $item->debit ? number_format($item->debit, 2) : '' }}
                </td>
                <td class="w-1/6 border-x border-black text-right">
                    {{ $item->credit ? number_format($item->credit, 2) : '' }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="border border-black uppercase"></td>
                <td colspan="2" class="border border-black px-2 font-bold">TOTAL:</td>
                <td class="border border-black font-bold text-right">
                    {{ $disbursement_voucher_items->sum('debit') ? number_format($disbursement_voucher_items->sum('debit'), 2) : '' }}
                </td>
                <td class="border border-black font-bold text-right">
                    {{ $disbursement_voucher_items->sum('credit') ? number_format($disbursement_voucher_items->sum('credit'), 2) : '' }}
                </td>
            </tr>
            <tr>
                <td class="whitespace-nowrap border-b border-black px-2 pt-4">Check No. <span class="px-4 border-b border-black">{{ $disbursement_voucher->check_number }}</span></td>
                <td class="whitespace-nowrap border-b border-r border-black px-2 pt-4">Amount P<span class="px-4 border-b border-black">{{ number_format($disbursement_voucher_items->last()->credit, 2) }}</span></td>
                <td colspan="4" class="px-2">DESCRIPTION OF ENTRY</td>
            </tr>
            <tr>
                <td class="w-1/6 border-r border-black px-2">Prepared and Certified Correct:</td>
                <td class="w-1/6 px-2">Funds Available:</td>
                <td colspan="4" class="border-b border-l border-black px-2">{{ $disbursement_voucher->description }}</td>
            </tr>
            <tr>
                <td class="border-r border-black"></td>
                <td class="border-r border-black"></td>
                <td class="border-r border-black px-2" colspan="2">Approved:</td>
                <td colspan="2" class="px-2">Received by:</td>
            </tr>
            <tr>
                <td class="border-r border-black p-0">&nbsp;</td>
                <td class="border-r border-black p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
                <td class="border-r border-black p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
                <td class="p-0">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-b border-r border-black p-0">&nbsp;</td>
                <td class="border-b border-r border-black p-0">&nbsp;</td>
                <td class="border-b border-black p-0">&nbsp;</td>
                <td class="border-b border-r border-black p-0">&nbsp;</td>
                <td class="border-b border-black p-0">&nbsp;</td>
                <td class="border-b border-black p-0">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-r border-black px-2 text-center">Bookkeeper</td>
                <td class="border-r border-black px-2 text-center">Treasurer</td>
                <td class="w-1/6 px-2">General Manager</td>
                <td class="w-1/6 border-r border-black">BOD Chairman</td>
                <td class="whitespace-nowrap px-2">Name & Signature/Date</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, 'Disbursement Voucher')">Print</x-filament::button>
    </div>
</div>